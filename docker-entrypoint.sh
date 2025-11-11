#!/bin/bash

# --- docker-entrypoint.sh ---
# Este script se encarga de iniciar los servicios necesarios 
# (PHP-FPM y Nginx) dentro del contenedor de manera segura.

# Detiene la ejecución si algún comando falla
set -e

# Esta sección es opcional y se utiliza para ejecutar comandos de Laravel
# (como cache:clear, migrate, etc.) justo antes de iniciar los servidores.
# if [ -f /var/www/artisan ]; then
#     echo "Ejecutando comandos de Laravel como 'cache:clear' y 'config:cache'..."
#     # su - www-data -s /bin/bash -c "php /var/www/artisan cache:clear"
#     # su - www-data -s /bin/bash -c "php /var/www/artisan config:cache"
# fi

# 1. Iniciar PHP-FPM en segundo plano
# La opción -D lo ejecuta como un demonio y lo devuelve al fondo.
echo "Iniciando PHP-FPM..."
/usr/sbin/php-fpm7.4 -D

# 2. Iniciar Nginx en primer plano
# Utilizamos 'exec' para que Nginx tome el PID 1, asegurando que Docker 
# pueda manejar correctamente las señales de apagado del contenedor.
echo "Iniciando Nginx..."
exec nginx -g "daemon off;"