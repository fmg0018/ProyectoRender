FROM php:8.2-fpm-alpine

# Argumentos de compilación para la versión de la aplicación
ARG APP_VERSION

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    build-base \
    libxml2-dev \
    sqlite-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    mysql-client \
    imagemagick-dev

# Instalar extensiones PHP requeridas por Laravel y composer
RUN docker-php-ext-install pdo_mysql opcache bcmath exif \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Configurar el usuario y directorio de trabajo
WORKDIR /var/www/html

# 3. Copiar la aplicación
# Copiar archivos de configuración de Docker (nginx, supervisor, entrypoint)
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Dar permisos de ejecución al script de entrada
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copiar el código de la aplicación
COPY . .

# 4. Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# 5. Configuración de Nginx y Permisos: Crear logs, configurar el usuario, y limpiar.
RUN mkdir -p /var/www/html/public \
    && adduser -D -g 'www-data' www-data \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx

# Exponer el puerto de Nginx (80)
EXPOSE 80

# 6. Definir el punto de entrada (Ejecuta el script de setup)
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# 7. Comando principal (Inicia Supervisor, que a su vez inicia Nginx y PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]