FROM php:8.2-fpm-alpine

# 1. INSTALACIÓN DE DEPENDENCIAS DEL SISTEMA
# Se añade tini para la gestión de procesos (PID 1) en contenedores.
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    git \
    build-base \
    libxml2-dev \
    nodejs \
    npm \
    libpq-dev \
    linux-headers \
    tini \
    && rm -rf /var/cache/apk/*

# 2. INSTALACIÓN DE EXTENSIONES DE PHP
RUN docker-php-ext-install pdo pdo_pgsql bcmath opcache sockets

# 3. INSTALACIÓN DE COMPOSER
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. CONFIGURACIÓN DEL DIRECTORIO DE TRABAJO
WORKDIR /var/www/html

# 5. COPIA DEL CÓDIGO
COPY . .

# 6. COMANDOS DE CONSTRUCCIÓN (Build)
# 6a: Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist

# 6b: Instalar y compilar dependencias de Node.
RUN npm install \
    && npm run build

# 7. PERMISOS
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 8. CONFIGURACIÓN DE SERVIDOR Y ARRANQUE
#
# AHORA SE COPIA A LAS RUTAS CORRECTAS:
# a) Configuración principal de Supervisor (con [supervisord])
COPY ./supervisord.conf /etc/supervisord.conf

# b) Definición de programas (con [program:...])
COPY ./supervisor_programs.conf /etc/supervisor/conf.d/programs.conf

# c) Configuración de Nginx.
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# 9. PUERTO Y COMANDO DE INICIO
EXPOSE 8080

# Usamos 'tini' como init process y llamamos a supervisord con la ruta de config principal.
CMD ["/usr/bin/tini", "--", "/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]