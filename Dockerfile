# -----------------------------------------------------------
# Etapa 1: Build/Base - Configura el entorno de PHP
# -----------------------------------------------------------
FROM php:8.2-fpm-alpine AS base

# Definir variables de entorno para el entorno de producción
ENV APP_ENV=production
ENV PATH="./vendor/bin:$PATH"

# Instalar dependencias del sistema necesarias
# Incluye paquetes necesarios para PHP, compilación (g++, make), y servicios (nginx, supervisor)
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    g++ \
    make \
    autoconf \
    libzip-dev \
    sqlite-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    pcre-dev \
    && rm -rf /var/cache/apk/*

# Instalar y habilitar extensiones de PHP comunes para Laravel
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    zip \
    gd \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    opcache

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------
# Etapa 2: Producción - Configuración de la aplicación y servicios
# -----------------------------------------------------------
FROM base AS final

# Establecer el directorio de trabajo para la aplicación
WORKDIR /var/www/html

# Copiar el código de la aplicación desde el contexto de build
COPY . /var/www/html

# Instalar dependencias de Laravel sin archivos de desarrollo
RUN composer install --no-dev --optimize-autoloader

# Limpiar cache y configurar permisos de Laravel
# 'www-data' es el usuario que usa PHP-FPM en Alpine
RUN php artisan optimize:clear \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Exponer el puerto de Nginx
EXPOSE 8000

# --- COPIAR ARCHIVOS DE CONFIGURACIÓN (RUTAS CORREGIDAS DENTRO DE ./.docker/) ---

# Copia de la configuración de Nginx al directorio de configuración
COPY ./.docker/NGINX/default.conf /etc/nginx/conf.d/default.conf

# Copia de la configuración de PHP-FPM (pool) al directorio de PHP-FPM
COPY ./.docker/PHP-FPM/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copia de la configuración de Supervisor al directorio raíz de configuración
COPY ./.docker/supervisor/supervisord.conf /etc/supervisord.conf

# Copiar y dar permisos de ejecución al script de entrada (Entrypoint Script)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# El contenedor inicia ejecutando el entrypoint, que a su vez inicia Supervisor
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]