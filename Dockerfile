# ------------------------------------------------------------------------------------------
# Multi-stage Dockerfile para producción de Laravel
# ------------------------------------------------------------------------------------------

# ==========================================================================================
# STAGE 1: COMPOSER (Construcción/Instalación de Dependencias)
# Usa la imagen oficial de Composer para descargar las dependencias de PHP.
# ==========================================================================================
FROM composer:latest AS composer

# Establece el directorio de trabajo dentro del contenedor
WORKDIR /app

# Copia el código fuente de la aplicación al contenedor.
COPY . .

# Instala las dependencias de PHP.
# Usamos --no-dev para producción y --optimize-autoloader para mejorar el rendimiento.
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ==========================================================================================
# STAGE 2: BUILD (Frontend Assets)
# Usa un entorno Node.js para compilar los assets de frontend (Vite/Mix)
# Asume que estás usando Node 20 o superior.
# ==========================================================================================
FROM node:20 AS build

WORKDIR /app

# Copia los archivos de configuración y código necesarios
COPY package.json package-lock.json ./
COPY vite.config.js ./
COPY resources/ resources/

# Instala dependencias de Node.js
RUN npm install

# Compila los assets para producción.
# Esto genera los archivos estáticos en la carpeta 'public'.
RUN npm run build

# ==========================================================================================
# STAGE 3: RUNTIME (Servidor Final - PHP-FPM con Alpine)
# Utiliza una imagen ligera de PHP para el servidor final.
# ==========================================================================================
FROM php:8.3-fpm-alpine AS final

# Instalación de dependencias de sistema y extensiones PHP
# 'make' es temporal, se elimina al final.
# mysqli es crucial para bases de datos MySQL/MariaDB.
RUN apk add --no-cache \
    curl \
    git \
    make \
    mariadb-client \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mysqli opcache

# Configuración de Opcache: Copia la configuración recomendada
# Esta es una configuración estándar para producción que reemplaza la de Octane.
COPY --from=composer /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/opcache-prod.ini

# Establece el directorio de trabajo para la aplicación
WORKDIR /var/www

# Copia la aplicación desde la etapa 'composer'
COPY --from=composer /app /var/www

# Copia los assets compilados desde la etapa 'build'
COPY --from=build /app/public /var/www/public

# Asegura que PHP-FPM tenga permisos sobre la aplicación
# Usa 'www-data' que es el usuario predeterminado de PHP-FPM
RUN chown -R www-data:www-data /var/www

# Exponer el puerto de PHP-FPM (9000). Nota: Nginx/Caddy lo usarán internamente.
# Si solo usas PHP (sin servidor web), podrías usar este puerto, pero en un
# entorno real como Render, el servidor web (que Render puede inyectar) es clave.
EXPOSE 9000

# Comando por defecto para iniciar PHP-FPM (necesario para Render/servidor web)
CMD ["php-fpm"]