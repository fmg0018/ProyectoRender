#!/bin/bash
# Este script se ejecuta al inicio del contenedor.

# 1. Esperar un poco a que la base de datos (si estuviera en la misma red) esté lista.
# Esto ayuda a prevenir errores de conexión tempranos al iniciar.
echo "-> Esperando 10 segundos..."
sleep 10

# 2. Configurar permisos para escritura (storage y bootstrap/cache)
# CRÍTICO: El usuario 'www-data' necesita escribir aquí para cache y logs.
echo "-> Configurando permisos de escritura para www-data (Laravel)"
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# 3. Limpiar y optimizar la configuración de Laravel (CRÍTICO para producción)
echo "-> Limpiando y cacheadando configuraciones de Laravel"
php /var/www/html/artisan optimize:clear # Limpia cachés antiguas
php /var/www/html/artisan config:cache    # Cacha las variables de entorno para velocidad
php /var/www/html/artisan route:cache     # Cacha las rutas para velocidad
php /var/www/html/artisan view:cache      # Cacha las vistas

# 4. Ejecutar migraciones (para la primera vez y actualizaciones)
# El flag --force es obligatorio en un entorno de producción (sin interacción).
echo "-> Ejecutando migraciones de base de datos..."
php /var/www/html/artisan migrate --force

# 5. Iniciar Supervisor para que ejecute Nginx y PHP-FPM
# Este comando es el último, ya que toma el control de la ejecución del contenedor.
echo "-> Iniciando Supervisor (Nginx + PHP-FPM)..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf