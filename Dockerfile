# Usa la imagen base oficial de PHP 8.2 con FPM en Alpine
FROM php:8.2-fpm-alpine

# Argumentos de compilación para la versión de la aplicación
ARG APP_VERSION

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apk update && apk add --no-cache \
    # Gestores de servicio y utilidades
    nginx \
    supervisor \
    bash \
    curl \
    git \
    # Dependencias de compilación para las extensiones de PHP
    build-base \
    autoconf \
    # Dependencias de extensiones de PHP:
    libxml2-dev \
    sqlite-dev \
    mysql-client \
    mysql-dev \
    libzip-dev \
    libexif-dev \
    oniguruma-dev \
    imagemagick-dev \
    # Dependencias para GD (instalación de PHP después)
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    # Limpieza de caché
    && rm -rf /var/cache/apk/*

# Instalar extensiones PHP requeridas por Laravel y Composer
RUN docker-php-ext-install pdo_mysql opcache bcmath exif \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    # Configurar e instalar GD (Muy común en Laravel)
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Limpieza adicional de paquetes de compilación para reducir el tamaño de la imagen
RUN apk del --purge build-base autoconf mysql-dev libexif-dev libzip-dev oniguruma-dev imagemagick-dev libjpeg-turbo-dev libpng-dev freetype-dev

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Configurar el usuario y directorio de trabajo
# Crear un usuario 'www-data' (estándar)
RUN adduser -D -g 'www-data' www-data

WORKDIR /var/www/html

# 3. Copiar la aplicación
# Copiar archivos de configuración de Docker (nginx, supervisor, entrypoint)

# 🛑 RUTA AJUSTADA O ASUMIDA:
# Asumo que debes colocar supervisord.conf dentro de la carpeta .docker/
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
# También puedes copiar el php-fpm.conf si lo necesitas para configuración avanzada:
# COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Dar permisos de ejecución al script de entrada
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copiar el código de la aplicación
COPY . .

# 4. Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# 5. Configuración de Nginx y Permisos: Crear logs, configurar el usuario, y limpiar.
RUN mkdir -p /var/www/html/public \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log/nginx

# Exponer el puerto de Nginx (80)
EXPOSE 80

# 6. Definir el punto de entrada (Ejecuta el script de setup)
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# 7. Comando principal (Inicia Supervisor, que a su vez inicia Nginx y PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]