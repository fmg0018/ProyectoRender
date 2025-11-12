#!/bin/bash

# Este script se ejecuta al inicio del contenedor (ENTRYPOINT)

# Cambiar al directorio de la aplicación, esto es VITAL para que 'cp' y 'php artisan' funcionen
cd /var/www/html || { echo "Error: No se pudo cambiar al directorio /var/www/html"; exit 1; }

echo "Ejecutando Laravel Artisan comandos en runtime..."

# 1. Copiar .env.example a .env si no existe
if [ ! -f .env ]; then
    echo "Copiando .env.example a .env"
    cp .env.example .env
fi

# 2. Generar la clave de la aplicación si no existe
# Esto es necesario incluso si ya tienes una clave definida en el .env, para asegurar
# que Laravel la reconozca.
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate
fi

# 3. Borrar cachés de configuración y vistas (prevención de errores de caché viejos)
echo "Limpiando cachés de Laravel..."
php artisan config:clear
php artisan view:clear

# 4. Optimizar el framework para producción
echo "Cacheando configuración y rutas..."
php artisan config:cache
php artisan route:cache

# 5. Ejecutar migraciones
# Solo se ejecuta si la variable de entorno DB_CONNECTION tiene algún valor.
if [ -n "$DB_CONNECTION" ]; then
    echo "Ejecutando migraciones..."
    php artisan migrate --force
fi

# Ejecutar el comando principal del contenedor (CMD)
# Esto es lo que inicia Supervisor, que a su vez ejecuta Nginx y PHP-FPM
exec "$@"