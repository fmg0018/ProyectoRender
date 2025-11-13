
# Seccion 1: PHP-FPM como base
FROM php:8.2-fpm-alpine

# Setear un argumento para el entorno (production por defecto)
ARG APP_ENV=production

# Seccion 2: Dependencias del sistema y de PHP (Laravel)
RUN apk add --no-cache \
    git \
    supervisor \
    nginx \
    curl \
    oniguruma-dev \
    sqlite-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_sqlite pdo_mysql zip opcache sockets \
    && docker-php-ext-enable opcache \
    && rm -rf /var/cache/apk/*

# Seccion 3: Composer y Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación (solo si es necesario para composer)
# Las dependencias de producción ya se descargan localmente antes del build.
# COPY . .

# Instalar dependencias de PHP (si no están ya en el .gitignored)
# RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Seccion 4: Archivos de configuracion de Nginx/PHP-FPM/Supervisor
# CORRECCIÓN: Copiar el Nginx global minimalista (para corregir el fallo del PID y el usuario global)
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Copiar la configuración específica de la app (corregida con 0.0.0.0:80 y www-data)
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY .docker/supervisord.conf /etc/supervisord.conf

# Seccion 5: Entrypoint y permisos
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Permisos para Nginx/PHP-FPM y log
RUN mkdir -p /var/www/html/public \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/log/supervisor \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log

# Exponer el puerto (principalmente informativo, ya que Nginx escucha en 80)
EXPOSE 80

# Comando de inicio del contenedor
CMD ["/usr/local/bin/docker-entrypoint.sh"]