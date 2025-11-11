# ------------------------------------------------------------------------------------------
# Multi-stage Dockerfile para Laravel (Nginx + PHP-FPM)
# ------------------------------------------------------------------------------------------

# ==========================================================================================
# ETAPA 1: COMPOSER (Instalación de Dependencias PHP)
# ==========================================================================================
FROM composer:latest AS composer

WORKDIR /app
# Copia los archivos necesarios para Composer
COPY composer.json composer.lock ./
# Copia todo el resto del código
COPY . .
# Instala las dependencias de producción
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ==========================================================================================
# ETAPA 2: BUILD (Compilación de Assets Frontend con Node/Vite)
# ==========================================================================================
FROM node:20 AS build

WORKDIR /app
# Copia los archivos de Node
COPY package.json package-lock.json ./
# Copia la configuración de Vite y la carpeta de recursos
COPY vite.config.js ./
COPY resources/ resources/
# Instala las dependencias y compila
RUN npm install
RUN npm run build

# ==========================================================================================
# ETAPA 3: RUNTIME (Servidor Final - Nginx + PHP-FPM)
# Usamos nginx:stable-alpine como base para un servidor ligero.
# ==========================================================================================
FROM nginx:stable-alpine AS final

# Instalar dependencias de sistema y PHP-FPM (versión 8.3 de PHP)
RUN apk add --no-cache \
    php83-fpm \
    php83-mysqli \
    php83-pdo_mysql \
    php83-opcache \
    php83-zip \
    php83-json \
    php83-dom \
    php83-ctype \
    php83-session \
    && rm -rf /var/cache/apk/* /tmp/*

# --- Configuración de PHP-FPM ---
# Copia la configuración de opcache
COPY --from=composer /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini /etc/php83/conf.d/opcache-prod.ini
# Configura PHP-FPM para escuchar en el puerto 9000 y usar el usuario 'nginx'
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 9000/' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/user = nobody/user = nginx/' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/group = nobody/group = nginx/' /etc/php83/php-fpm.d/www.conf

# --- Configuración de Nginx ---
# Copia el archivo de configuración del sitio (debe tener el 'listen 80')
COPY .docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf
# Establece el directorio de trabajo (ROOT de la aplicación)
WORKDIR /var/www

# Copia el código de Laravel (Archivos PHP y Vendor)
COPY --from=composer /app /var/www

# Copia los assets compilados a la carpeta public de Laravel
COPY --from=build /app/public /var/www/public

# Permisos de Laravel (storage) para el usuario 'nginx'
RUN chown -R nginx:nginx /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Render buscará este puerto (80)
EXPOSE 80

# Comando final: Arranca PHP-FPM en background (&&) y Nginx en foreground.
# Esta es la línea clave para que ambos servicios corran en un solo contenedor.
CMD php-fpm83 && nginx -g 'daemon off;'