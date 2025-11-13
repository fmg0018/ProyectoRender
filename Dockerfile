# Seccion 1: PHP-FPM como base
FROM php:8.2-fpm-alpine

# Setear un argumento para el entorno (production por defecto)
ARG APP_ENV=production

# Seccion 2: Dependencias del sistema y de PHP (Laravel)
# Supervisor ha sido ELIMINADO.
RUN apk add --no-cache \
    git \
    nginx \
    curl \
    oniguruma-dev \
    sqlite-dev \
    libxml2-dev \
    libzip-dev \
    linux-headers \
    && docker-php-ext-install pdo_sqlite pdo_mysql zip opcache sockets \
    && docker-php-ext-enable opcache \
    && rm -rf /var/cache/apk/*

# Seccion 3: Composer y Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP (si no están ya en el .gitignored)
# RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Seccion 4: Archivos de configuracion de Nginx/PHP-FPM
# Copia la configuracion global de Nginx (corregida)
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
# Copia la configuracion de la app (corregida)
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
# Copia la configuracion de PHP-FPM
COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

# SECCION 5: ENTRYPOINT (EL NUEVO GESTOR DE INICIO)
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# SECCIÓN 6: PERMISOS
RUN mkdir -p /var/www/html/public \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/log/supervisor \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log

# Exponer el puerto
EXPOSE 80

# COMANDO DE INICIO FINAL: Ejecuta el script customizado
CMD ["/usr/local/bin/docker-entrypoint.sh"]