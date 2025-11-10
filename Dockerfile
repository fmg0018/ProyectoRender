FROM php:8.2-fpm-alpine

# 2. INSTALACIÓN DE DEPENDENCIAS DEL SISTEMA
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
    && rm -rf /var/cache/apk/*

# 3. INSTALACIÓN DE EXTENSIONES DE PHP
RUN docker-php-ext-install pdo pdo_pgsql bcmath opcache sockets

# 4. INSTALACIÓN DE COMPOSER
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. CONFIGURACIÓN DEL DIRECTORIO DE TRABAJO
WORKDIR /var/www/html

# 6. COPIA DEL CÓDIGO
COPY . .

# 7. COMANDOS DE CONSTRUCCIÓN (Build)
# 7a: Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist

# 7b: Instalar y compilar dependencias de Node.
# Esto es más estable que un comando RUN largo.
RUN npm install \
    && npm run build

# 8. PERMISOS
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 9. CONFIGURACIÓN DE SERVIDOR Y ARRANQUE
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 10. PUERTO Y COMANDO DE INICIO
EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]