#!/bin/sh
set -e

# Iniciar PHP-FPM en segundo plano (para que Nginx pueda comunicarse con él)
# Usamos el path completo en Alpine
/usr/local/sbin/php-fpm &

# Iniciar Nginx en primer plano usando 'exec'. 
# Esto reemplaza el proceso actual del shell, asegurando que 
# Nginx es el proceso principal (PID 1) y el contenedor se mantiene vivo.
exec /usr/sbin/nginx