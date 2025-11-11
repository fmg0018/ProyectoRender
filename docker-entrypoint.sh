#!/bin/sh
set -e

# --- 1. PREPARACIÓN DE LARAVEL EN RUNTIME ---
echo "Ejecutando Laravel Artisan comandos en runtime..."

# 1. Copiar .env si no existe (Crucial para que Laravel inicie)
if [ ! -f .env ]; then
    echo "Copiando .env.example a .env"
    cp .env.example .env
fi

# 2. Generar APP_KEY si no está definida
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generando APP_KEY..."
    # Ejecutamos key:generate. Esto también actualiza el .env
    php /var/www/artisan key:generate
fi

# 3. Limpiar y cachear configuración
echo "Limpiando y cacheando la configuración..."

# Ajustar permisos antes de escribir la caché (www-data es el usuario de PHP-FPM)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

php /var/www/artisan config:clear
php /var/www/artisan cache:clear
php /var/www/artisan config:cache
php /var/www/artisan route:cache

# 4. Ejecutar migraciones (Necesario para la base de datos)
echo "Ejecutando migraciones..."
php /var/www/artisan migrate --force

# --- 2. INICIO DE SERVICIOS ---

# Iniciar PHP-FPM en segundo plano (usamos el ejecutable simple 'php-fpm')
echo "Iniciando PHP-FPM..."
php-fpm -D

# Iniciar Nginx en primer plano
echo "Iniciando Nginx..."
exec nginx -g "daemon off;"