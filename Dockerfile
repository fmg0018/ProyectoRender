# -------------------------------------------------------------------------
# ETAPA 1: BUILDER - Configuración de Node.js y PHP para la compilación
# -------------------------------------------------------------------------
FROM node:20.10-alpine as node_builder

WORKDIR /app

# Instalar dependencias del frontend si usas NPM/Yarn para assets
# COMENTAR O ELIMINAR ESTAS LÍNEAS SI NO USAS FRONTEND (Blade simple/Sin Vite)
# COPY package.json package-lock.json ./
# RUN npm install
# COPY . .
# RUN npm run build

# -------------------------------------------------------------------------
# ETAPA 2: PRODUCTION - Imagen base final con PHP y Nginx
# -------------------------------------------------------------------------
# Usar la imagen oficial de PHP FPM (FastCGI Process Manager)
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema requeridas para Laravel/PHP
RUN apk update && apk add \
    nginx \
    git \
    supervisor \
    openssl \
    bash \
    # Dependencias PHP requeridas:
    icu-dev \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg-turbo-dev \
    postgresql-dev \
    onig \
    && rm -rf /var/cache/apk/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql opcache zip bcmath exif gd intl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación (incluyendo .env.example)
COPY . .

# Corregir permisos de almacenamiento para Laravel (storage, bootstrap/cache)
# Es vital que el usuario 'www-data' (el usuario de PHP-FPM) pueda escribir aquí
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Copiar la configuración personalizada de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar la configuración de Supervisor (para correr FPM y Nginx juntos)
COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

# Copiar el script de entrada (Entrypoint Script)
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto de Nginx
EXPOSE 8000

# Usar el entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Comando por defecto para iniciar Supervisor (que a su vez inicia Nginx y PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]