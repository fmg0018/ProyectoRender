
# ----------------------------------------------------
# 1. ETAPA BASE: Instalación de PHP, Nginx, Supervisor y dependencias
# ----------------------------------------------------
FROM php:8.2-fpm-alpine

# Argumentos para Node.js (se mantienen)
ARG NODE_VERSION=20
ENV PATH="/root/.local/bin:$PATH"

# Instalar dependencias del sistema operativo (Alpine)
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    build-base \
    autoconf \
    libxml2-dev \
    sqlite-dev \
    # Dependencias de desarrollo para extensiones de PHP (CORREGIDO)
    mysql-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    zlib-dev \
    # Dependencias de frontend
    nodejs \
    npm \
    # Cliente de MySQL para ejecutar comandos si es necesario
    mysql-client

# Instalar y configurar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql opcache bcmath exif zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# ----------------------------------------------------
# 2. CONFIGURACIÓN DE LARAVEL/COMPOSER/VITE
# ----------------------------------------------------

# Copiar Composer (Multistage Build)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear el directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto al contenedor
COPY . .

# Instalar dependencias de PHP (Laravel)
# Usamos '--no-dev' ya que los servidores de producción no lo necesitan, pero puedes cambiarlo si trabajas en desarrollo
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Construir las dependencias de Frontend (Vite)
# Esto resuelve la excepción ViteManifestNotFoundException
RUN npm install
RUN npm run build
# ----------------------------------------------------

# ----------------------------------------------------
# 3. CONFIGURACIÓN FINAL: Nginx, Supervisor y Permisos
# ----------------------------------------------------

# Copiar archivos de configuración
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

# Crear el script de entrypoint y darle permisos de ejecución
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Configuración de permisos finales
# 🟢 CORRECCIÓN CLAVE: Creamos el directorio de log de Supervisor
RUN mkdir -p /var/www/html/public \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/log/supervisor \ 
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log

# Exponer los puertos
EXPOSE 80
EXPOSE 9000

# Punto de entrada del contenedor (Supervisor inicia Nginx y PHP-FPM)
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]