# Usa la imagen base de PHP oficial con FPM en Alpine
FROM php:8.3-fpm-alpine

# Argumento para el directorio de la aplicación (por defecto)
ARG APP_DIR=/var/www/html

# Instalar dependencias del sistema operativo (Nginx, Supervisor, y extensiones PHP)
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    \
    # --- 1. DEPENDENCIAS DE COMPILACIÓN (Necesarias para zip, intl, pdo) ---
    autoconf \
    build-base \
    bison \
    re2c \
    \
    # --- 2. DEPENDENCIAS DE RUNTIME Y DESARROLLO (Librerías C/Headers) ---
    git \
    composer \
    libxml2-dev \
    sqlite-dev \
    postgresql-dev \
    libzip-dev \
    icu-dev \
    file \
    \
    # --- 3. EXTENSIONES PHP PRE-COMPILADAS (Para evitar el error del 'tokenizer') ---
    # Instalamos dom, session, fileinfo y tokenizer directamente como paquetes Alpine
    php83-dom \
    php83-session \
    php83-fileinfo \
    php83-tokenizer \
    \
    # Aseguramos que Nginx se ejecute con el mismo usuario que FPM (Corregido el error inicial)
    && chown -R www-data:www-data /var/lib/nginx /var/www/html \
    \
    # --- 4. EXTENSIONES PHP COMPILADAS (Las que se vinculan a las librerías -dev) ---
    && docker-php-ext-install \
    pdo_mysql \
    pdo_sqlite \
    zip \
    intl \
    \
    # --- 5. LIMPIEZA DE DEPENDENCIAS DE COMPILACIÓN (Reducimos el tamaño de la imagen) ---
    && apk del --no-cache \
    autoconf \
    build-base \
    bison \
    re2c \
    \
    # Limpiar caché residual
    && rm -rf /var/cache/apk/*

# Solución para la advertencia de Git sobre propiedad de directorio
RUN git config --global --add safe.directory ${APP_DIR}

# Establecer el directorio de trabajo
WORKDIR ${APP_DIR}

# 1. Copiar la aplicación de Laravel
COPY . ${APP_DIR}

# 2. Instalar dependencias de Composer (Laravel)
RUN composer install --no-dev --optimize-autoloader

# 3. Configurar permisos CRUCIALES para Laravel
RUN chown -R www-data:www-data ${APP_DIR}/storage \
    && chown -R www-data:www-data ${APP_DIR}/bootstrap/cache \
    && chmod -R 775 ${APP_DIR}/storage \
    && chmod -R 775 ${APP_DIR}/bootstrap/cache

# 4. Copiar configuraciones de Supervisor y Nginx
COPY ./supervisord.conf /etc/supervisord.conf
COPY ./supervisor_programs.conf /etc/supervisor/conf.d/programs.conf
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# 5. Exponer el puerto
EXPOSE 8080

# 6. Ejecutar Supervisor (ENTRYPOINT)
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]