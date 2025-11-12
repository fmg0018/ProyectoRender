#!/bin/sh
# Este script se ejecuta al inicio del contenedor (ENTRYPOINT)

# Cambiar al directorio de la aplicación, esto es VITAL para que 'php artisan' funcione
cd /var/www/html || { echo "Error: No se pudo cambiar al directorio /var/www/html"; exit 1; }

echo "--- Iniciando script de entrada de Laravel ---"

# 1. Copiar .env.example a .env si no existe
if [ ! -f .env ]; then
    echo "Copiando .env.example a .env"
    cp .env.example .env
fi

# 2. Asignar permisos al usuario www-data (necesario para Laravel)
echo "Configurando permisos de escritura para storage y caché..."
chown -R www-data:www-data /var/www/html
chmod -R 775 storage bootstrap/cache

# 3. Generar la clave de la aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
fi

# 4. Borrar cachés de configuración y vistas (prevención de errores de caché viejos)
echo "Limpiando cachés de Laravel..."
php artisan config:clear
php artisan view:clear

# 5. Optimizar el framework para producción (o desarrollo, según APP_ENV)
echo "Cacheando configuración y rutas..."
php artisan config:cache
php artisan route:cache

# 6. Ejecutar migraciones (se asume que debe intentarlo)
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force --no-interaction

echo "--- Configuración de Laravel finalizada ---"

# El comando 'exec' es fundamental. Ejecuta el CMD del Dockerfile, que debería ser Supervisor.
exec "$@"