# ------------------------------------------------------------------------------------------
# Multi-stage Dockerfile para Laravel (Nginx + PHP-FPM)
#
# Este archivo define cómo se construye la imagen de Docker que Render usará.
# Está dividido en etapas para optimizar el tamaño final de la imagen.
# ------------------------------------------------------------------------------------------

# ==========================================================================================
# ETAPA 1: COMPOSER (Instalación de Dependencias PHP)
# ==========================================================================================
FROM composer:latest AS composer

WORKDIR /app
# Copia los archivos necesarios para Composer
COPY composer.json composer.lock ./
# Copia todo el resto del código del proyecto
COPY . .
# Instala las dependencias de producción (sin desarrollo)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ==========================================================================================
# ETAPA 2: BUILD (Compilación de Assets Frontend con Node/Vite)
# ==========================================================================================
FROM node:20 AS build

WORKDIR /app
# Copia archivos de Node
COPY package.json package-lock.json ./
# Copia la configuración de Vite y la carpeta de recursos
COPY vite.config.js ./
COPY resources/ resources/
# Instala las dependencias y compila los assets
RUN npm install
RUN npm run build

# ==========================================================================================
# ETAPA 3: RUNTIME (Servidor Final - Nginx + PHP-FPM)
# Usamos nginx:stable-alpine como base para un servidor ligero.
# ==========================================================================================
FROM nginx:stable-alpine AS final

# Instalar PHP-FPM y las extensiones necesarias
# Usamos 'php83' para asegurar compatibilidad.
RUN apk add --no-cache \
    php83 \
    php83-fpm \
    php83-mysqli \
    php83-pdo_mysql \
    php83-opcache \
    php83-zip \
    php83-json \
    php83-dom \
    php83-ctype \
    php83-session \
    php83-mbstring \
    php83-gd \
    # Limpieza de caché para reducir tamaño
    && rm -rf /var/cache/apk/* /tmp/*

# --- Configuración de PHP-FPM ---
# Copia la configuración de opcache
COPY --from=composer /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini /etc/php83/conf.d/opcache-prod.ini
# Configura PHP-FPM para escuchar en el puerto 9000 y usar el usuario 'nginx'
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 9000/' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/user = nobody/user = nginx/' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/group = nobody/group = nginx/' /etc/php83/php-fpm.d/www.conf

# --- Configuración de Nginx ---
# Copia el archivo de configuración del sitio
# Asume que tienes el archivo .docker/nginx/nginx.conf
COPY .docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf
# Establece el directorio de trabajo (ROOT de la aplicación)
WORKDIR /var/www

# Copia el código de Laravel (Archivos PHP y Vendor) desde la etapa Composer
COPY --from=composer /app /var/www

# Copia los assets compilados a la carpeta public de Laravel desde la etapa Build
COPY --from=build /app/public /var/www/public

# Generar la clave de la aplicación (APP_KEY) si no existe. 
# Esto es crítico para que Laravel funcione.
RUN if [ ! -f .env ] ; then cp .env.example .env; fi \
    && php83 artisan key:generate

# Permisos de Laravel (storage) para el usuario 'nginx'
RUN chown -R nginx:nginx /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Puerto expuesto (Render usará este puerto 80)
EXPOSE 80

# Comando de arranque final: Arranca PHP-FPM en background (&&) y Nginx en foreground.
# Esta línea mantiene el contenedor corriendo.
CMD php-fpm83 && nginx -g 'daemon off;'