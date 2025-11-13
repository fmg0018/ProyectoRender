#!/bin/bash
set -e

# Establece el UID (User ID) y GID (Group ID) de 'www-data'
# Usaremos 1000 como estándar en Alpine para www-data, no es necesario remapear aquí.

# 1. Ajuste de permisos (Crucial para Laravel)
echo "-> Ajustando permisos para /var/www/html..."
# Asegura que el owner sea www-data
chown -R www-data:www-data /var/www/html

# Asegurar que los directorios de caché y logs sean escribibles (esencial para Laravel)
echo "-> Ajustando permisos para storage/ y bootstrap/cache"
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# 2. Generación de claves y caché (Solo si el comando principal es Supervisor)
if [ "$1" = "/usr/bin/supervisord" ]; then
    echo "-> Verificando entorno de Laravel..."

    # Si no existe la clave de aplicación, la genera (crucial para Laravel)
    if [ ! -f /var/www/html/.env ]; then
        echo "Copiando .env.example a .env"
        cp /var/www/html/.env.example /var/www/html/.env
    fi

    # Si la clave de la aplicación no está establecida, la genera
    if [ "${APP_KEY}" = "" ]; then
        echo "Generando APP_KEY..."
        php /var/www/html/artisan key:generate --force
    fi
    
    # Optimizaciones de Laravel
    echo "Optimizando Laravel..."
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache
    php /var/www/html/artisan view:cache

    # 3. Ejecución del comando principal: Supervisor
    # Usamos 'exec' para reemplazar el shell por el proceso de Supervisor (PID 1)
    # Usamos la sintaxis con -n para forzar el modo foreground (no-daemonize)
    echo "-> Iniciando Supervisor en foreground: $@ (args: $2 $3)"
    exec /usr/bin/supervisord -n -c /etc/supervisord.conf
fi

# Si el comando NO es Supervisor, simplemente ejecuta el comando original (e.g., 'bash')
echo "-> Ejecutando comando: $@"
exec "$@"