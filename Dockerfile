# Seccion 1: PHP-FPM como base
FROM php:8.2-fpm-alpine

# Setear un argumento para el entorno (production por defecto)
ARG APP_ENV=production

# Seccion 2: Dependencias del sistema y de PHP (Laravel)
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

# Seccion 3: Composer, Codigo Fuente e Instalacion
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# *** CRUCIAL: COPIAR EL CODIGO FUENTE (incluyendo composer.json) ANTES DE INSTALAR ***
COPY . .

# INSTALACION CRITICA DE DEPENDENCIAS: AHORA ENCONTRARA composer.json
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Seccion 4: Archivos de configuracion de Nginx/PHP-FPM
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

# SECCION 5: ENTRYPOINT
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# SECCIÓN 6: PERMISOS
RUN mkdir -p /var/www/html/public \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log

# Exponer el puerto
EXPOSE 80

# COMANDO DE INICIO FINAL: Ejecuta el script customizado
CMD ["/usr/local/bin/docker-entrypoint.sh"]