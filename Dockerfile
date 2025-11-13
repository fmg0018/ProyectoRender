# ==============================================================================
# ETAPA 1: BUILDER DE FRONTEND (Compila activos con Node/Vite)
# ==============================================================================
FROM node:20-alpine as builder

# Directorio de trabajo
WORKDIR /app

# Copia los archivos de configuración de dependencias (npm/yarn)
COPY package.json package-lock.json ./

# Instala las dependencias de Node
RUN npm install

# Copia el código fuente restante de Laravel
COPY . .

# Compila los activos de producción (CSS/JS)
# Esto crea la carpeta public/build con manifest.json
RUN npm run build

# ==============================================================================
# ETAPA 2: IMAGEN FINAL DE PRODUCCIÓN (PHP-FPM, Nginx y Archivos Compilados)
# ==============================================================================
FROM php:8.2-fpm-alpine

# Setear un argumento para el entorno (production por defecto)
ARG APP_ENV=production

# Seccion 1: Dependencias del sistema y de PHP (Laravel)
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

# Seccion 2: Composer y Directorio de Trabajo
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo principal
WORKDIR /var/www/html

# Copiar el código fuente de Laravel (sin los módulos de Node)
COPY . .

# CRÍTICO: Copia los activos compilados (public/build) desde la etapa 'builder'
COPY --from=builder /app/public/build /var/www/html/public/build
COPY --from=builder /app/node_modules /var/www/html/node_modules
COPY --from=builder /app/package.json /var/www/html/package.json
COPY --from=builder /app/package-lock.json /var/www/html/package-lock.json


# INSTALACION DE DEPENDENCIAS DE PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# SECCION 3: CONFIGURACION DE LARAVEL (CLAVE Y PERMISOS)
# Copia .env.example si no hay .env (Render debería inyectar variables)
RUN cp .env.example .env

# Generar la clave de la aplicación.
RUN php artisan key:generate

# Configuración de permisos de cache y storage para el usuario www-data
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# Seccion 4: Archivos de configuracion de Nginx/PHP-FPM
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

# SECCION 5: ENTRYPOINT
COPY .docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# SECCIÓN 6: PERMISOS FINALES
RUN chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log

# Exponer el puerto
EXPOSE 80

# COMANDO DE INICIO FINAL: Ejecuta el script customizado
CMD ["/usr/local/bin/docker-entrypoint.sh"]