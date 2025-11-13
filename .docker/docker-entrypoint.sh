#!/bin/sh
# Este script se ejecuta al inicio del contenedor (ENTRYPOINT)
# Detiene el script inmediatamente si algún comando falla
set -e

# Cambiar al directorio de la aplicación, esto es VITAL
cd /var/www/html || { echo "Error: No se pudo cambiar al directorio /var/www/html"; exit 1; }

echo "--- Iniciando script de entrada de Laravel ---"

# 1. Copiar .env.example a .env si no existe
if [ ! -f .env ]; then
    echo "Copiando .env.example a .env"
    cp .env.example .env
fi

# 2. Configurar el usuario www-data como dueño de TODO el proyecto
# Esto es necesario antes de que Laravel pueda generar claves o caches.
echo "Configurando dueños para la aplicación..."
chown -R www-data:www-data /var/www/html

# 3. Asignar permisos de escritura a las carpetas críticas
# El usuario www-data es el que ejecuta PHP-FPM.
echo "Configurando permisos de escritura para storage y caché..."
chmod -R 775 storage bootstrap/cache

# 4. Generar la clave de la aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    # Se usa --force para evitar problemas de interacción si la aplicación piensa que está en prod
    php artisan key:generate --force
fi

# 5. Borrar cachés (necesario para asegurar que el .env copiado se lea)
echo "Limpiando cachés de Laravel..."
php artisan config:clear
php artisan view:clear

# 6. Optimizar el framework para producción
echo "Cacheando configuración y rutas..."
php artisan config:cache
php artisan route:cache

# 7. Ejecutar migraciones
echo "Ejecutando migraciones de base de datos..."
# --force y --no-interaction son esenciales en entornos no interactivos (Docker)
php artisan migrate --force --no-interaction

echo "--- Configuración de Laravel finalizada ---"

# El comando 'exec' es fundamental. Ejecuta el CMD del Dockerfile, que DEBE ser Supervisor.
exec "$@"