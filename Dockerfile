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
    # Dependencia para pdo_mysql (musl-dev es esencial en Alpine)
    mysql-client \
    mysql-dev \
    # Dependencia para zip
    libzip-dev \
    # Dependencia para exif
    libexif-dev \
    # Dependencia para bcmath, tokenizer
    oniguruma-dev \
    # Dependencia para la extensión gd (si se necesita, para imagen)
    imagemagick-dev \
    # Asegurar que se limpia la caché de apk para reducir el tamaño
    && rm -rf /var/cache/apk/*

# Instalar extensiones PHP requeridas por Laravel y composer
# Usamos docker-php-ext-install para las extensiones, configurando 'zip' y 'gd'
RUN docker-php-ext-install pdo_mysql opcache bcmath exif \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    # Instalar GD si necesitas manipulación de imágenes
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Limpieza adicional de paquetes de compilación después de instalar las extensiones
RUN apk del --purge build-base autoconf mysql-dev libexif-dev libzip-dev oniguruma-dev imagemagick-dev

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Configurar el usuario y directorio de trabajo
# Crear un usuario 'www-data' (estándar de Apache/Nginx/PHP)
RUN adduser -D -g 'www-data' www-data

WORKDIR /var/www/html

# 3. Copiar la aplicación
# Copiar archivos de configuración de Docker (nginx, supervisor, entrypoint)
# NOTA: Asegúrate de que supervisord.conf, .docker/nginx/default.conf, y .docker/docker-entrypoint.sh existan
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Dar permisos de ejecución al script de entrada
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copiar el código de la aplicación
COPY . .

# 4. Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# 5. Configuración de Nginx y Permisos: Crear logs, configurar el usuario, y limpiar.
RUN mkdir -p /var/www/html/public \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx

# Exponer el puerto de Nginx (80)
EXPOSE 80

# 6. Definir el punto de entrada (Ejecuta el script de setup)
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# 7. Comando principal (Inicia Supervisor, que a su vez inicia Nginx y PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]