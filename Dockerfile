# ----------------------------------------------------------------------
# Etapa 1: Builder (Instalación de dependencias de PHP y Laravel)
# Utiliza una imagen base de PHP-FPM para instalar Composer y dependencias.
# ----------------------------------------------------------------------
FROM php:7.4-fpm-buster as builder

# Definir argumentos de compilación y entorno
ARG UID=1000
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema, herramientas y extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    # Compilar extensiones de PHP necesarias (PostgreSQL, mbstring, gd, zip, etc.)
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    # Limpiar caché y archivos temporales
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario de aplicación 'appuser' (recomendado para seguridad)
RUN useradd -u $UID -ms /bin/bash appuser
WORKDIR /var/www

# Copiar el código fuente y establecer permisos
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
# Imagen ligera basada en Debian para producción
# ----------------------------------------------------------------------
FROM debian:buster-slim

ENV DEBIAN_FRONTEND=noninteractive
# Asegurar que los binarios estén en el PATH
ENV PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

# Instalar Nginx y procps (necesario para el script de entrada que gestiona procesos)
RUN apt-get update && apt-get install -y \
    nginx \
    procps \
    # Eliminar la configuración default de Nginx para usar la nuestra
    && rm -f /etc/nginx/sites-enabled/default \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# Copiar el runtime de PHP-FPM (binarios y configuraciones) desde la etapa builder
# Esto es CRUCIAL para que PHP-FPM esté disponible en la imagen final
COPY --from=builder /usr/lib/php /usr/lib/php
COPY --from=builder /usr/local/bin/php /usr/local/bin/php
COPY --from=builder /usr/local/etc/php /usr/local/etc/php
COPY --from=builder /etc/php /etc/php
COPY --from=builder /usr/sbin/php-fpm7.4 /usr/sbin/php-fpm7.4
RUN chmod +x /usr/sbin/php-fpm7.4

# Copiar el código de la aplicación (con vendor ya instalado)
# Se establece el usuario 'www-data' (estándar de Nginx/PHP) como propietario
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