# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de dependencias de Composer y compilación)
# Usamos PHP 8.2-FPM-Alpine: más ligero y estable.
# ----------------------------------------------------------------------
FROM php:8.2-fpm-alpine as builder

# Definir argumentos de compilación y entorno
ARG UID=1000
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema y extensiones de PHP (Alpine usa 'apk')
RUN apk update && apk add --no-cache \
    git \
    unzip \
    libxml2 \
    libpng \
    libpq \
    freetype \
    libjpeg \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libpq-dev \
    # Compilar y habilitar extensiones de PHP
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pdo_mysql opcache gd \
    # Limpieza: Eliminamos las dependencias de desarrollo/compilación
    && apk del --no-cache freetype-dev libjpeg-turbo-dev libpng-dev libxml2-dev libpq-dev

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario de aplicación 'appuser'
RUN addgroup -g 1000 appuser && adduser -u $UID -G appuser -s /bin/sh -D appuser
WORKDIR /var/www

# Copiar el código fuente y establecer permisos
COPY . /var/www
RUN chown -R appuser:appuser /var/www

# Instalar dependencias de Laravel (como usuario no root)
USER appuser
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# FIX DE PERMISOS DE ARTISAN: Aseguramos que 'storage' tenga permisos antes de usar artisan
RUN mkdir -p /var/www/storage/framework/cache \
    && chown -R appuser:appuser /var/www/storage

# Ejecutar comandos de Laravel (configuración en tiempo de construcción)
# FIX FINAL DE ARTISAN: Generamos la clave en bash para evitar el fallo de Artisan.
RUN php /var/www/artisan config:clear
RUN php /var/www/artisan cache:clear

# Generación de la APP_KEY directamente en el .env (solo si no existe)
# Este comando es el reemplazo seguro para 'php artisan key:generate'
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && if ! grep -q "^APP_KEY=base64:" .env; then echo "APP_KEY=$(php /var/www/artisan key:generate --show)" >> .env; fi

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de producción con Nginx y PHP-FPM)
# ----------------------------------------------------------------------
FROM php:8.2-fpm-alpine

# Instalar Nginx y procps
RUN apk update && apk add --no-cache nginx procps

# Copiar el código de la aplicación
COPY --from=builder --chown=www-data:www-data /var/www /var/www

# Configurar directorios finales de Laravel y socket de PHP-FPM
RUN mkdir -p /run/php \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar la configuración de Nginx 
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar y dar permisos de ejecución al script de entrada 
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Puerto de Nginx
EXPOSE 80

# Usar el script de entrada que inicia ambos servicios
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]