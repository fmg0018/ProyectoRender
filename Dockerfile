# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de dependencias de Composer)
# Usamos PHP 8.2-FPM-Alpine para la compilación, base más estable y ligera.
# ----------------------------------------------------------------------
FROM php:8.2-fpm-alpine as builder

# Definir argumentos de compilación y entorno
ARG UID=1000
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema y extensiones de PHP (Alpine usa 'apk')
RUN apk update && apk add --no-cache \
    git \
    unzip \
    libxml2-dev \
    libpng-dev \
    libpq \
    libpq-dev \
    # Instalar extensiones con el instalador de Docker
    && docker-php-ext-install pdo_pgsql pdo_mysql opcache \
    # Extensiones que requieren compilación manual en Alpine
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apk del libpng-dev libxml2-dev

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario de aplicación 'appuser' (recomendado para seguridad)
# El ID 1000 es estándar, y el usuario 'www-data' ya existe.
RUN addgroup -g 1000 appuser && adduser -u $UID -G appuser -s /bin/sh -D appuser
WORKDIR /var/www

# Copiar el código fuente
COPY . /var/www
RUN chown -R appuser:appuser /var/www

# Instalar dependencias de Laravel (como usuario no root)
USER appuser
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Ejecutar comandos de Laravel (configuración en tiempo de construcción)
RUN php artisan key:generate
RUN php artisan config:clear
RUN php artisan cache:clear

# ----------------------------------------------------------------------
# Etapa 2: Final (Imagen de producción con Nginx y PHP-FPM)
# Usamos la misma base Alpine.
# ----------------------------------------------------------------------
FROM php:8.2-fpm-alpine

# Instalar Nginx (Alpine usa 'apk' que es más robusto contra fallos de red)
RUN apk update && apk add --no-cache nginx procps

# Copiar el código de la aplicación (con vendor ya instalado)
# El usuario 'www-data' ya existe en esta base
COPY --from=builder --chown=www-data:www-data /var/www /var/www

# Configurar directorios de cache/storage de Laravel y socket de PHP-FPM
RUN mkdir -p /run/php \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar la configuración de Nginx (debe existir en .docker/nginx/default.conf)
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar y dar permisos de ejecución al script de entrada (debe existir en la raíz)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Puerto de Nginx
EXPOSE 80

# Usar el script de entrada que inicia ambos servicios
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]