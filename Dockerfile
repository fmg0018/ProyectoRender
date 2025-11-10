Entendido. Para evitar que el código se corte o se muestre mal, lo presentaré en un formato más amplio y lo separaré claramente para que puedas copiar y pegar cada archivo individualmente.

Recuerda: Necesitas crear estos tres archivos exactamente con estos nombres y subir los tres a la raíz de tu repositorio en GitHub.

1. Archivo Dockerfile (Instrucciones de Construcción)
Este es el archivo principal que le dice a Docker cómo crear el entorno de PHP, Nginx, Composer y Node.

Dockerfile

# 1. BASE IMAGE: Usamos PHP-FPM y Alpine (imagen ligera)
FROM php:8.2-fpm-alpine

# 2. INSTALACIÓN DE DEPENDENCIAS DEL SISTEMA
# Instalamos nginx, supervisor (para manejar procesos), git, Node/npm y dependencias de PostgreSQL.
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    git \
    build-base \
    libxml2-dev \
    nodejs \
    npm \
    libpq-dev \
    && rm -rf /var/cache/apk/*

# 3. INSTALACIÓN DE EXTENSIONES DE PHP
# Instalamos pdo_pgsql para la base de datos de Render y otras extensiones de Laravel.
RUN docker-php-ext-install pdo pdo_pgsql bcmath opcache sockets

# 4. INSTALACIÓN DE COMPOSER
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. CONFIGURACIÓN DEL DIRECTORIO DE TRABAJO
WORKDIR /var/www/html

# 6. COPIA DEL CÓDIGO
COPY . .

# 7. COMANDOS DE CONSTRUCCIÓN (Build)
# Instalamos dependencias de PHP, de Node, compilamos assets y generamos la clave.
RUN composer install --no-dev --prefer-dist \
    && npm install \
    && npm run build \
    && php artisan key:generate

# 8. PERMISOS
# Configuramos permisos para el usuario 'www-data' que ejecuta PHP-FPM.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 9. CONFIGURACIÓN DE SERVIDOR Y ARRANQUE
# Copiamos los archivos de configuración de Nginx y Supervisor.
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 10. PUERTO Y COMANDO DE INICIO
EXPOSE 8080
# El comando de inicio ejecuta Supervisor.
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]