Scheduler / marcar facturas vencidas automáticamente

El proyecto incluye un comando Artisan que marca como vencidas las facturas cuya fecha
 de vencimiento es anterior o igual al día actual:

- Comando: `php artisan invoices:mark-overdue`

Para que este comando se ejecute automáticamente en producción debes asegurarte de que
el Scheduler de Laravel se ejecute periódicamente en el servidor. A continuación tienes
varias opciones y ejemplos.

1) Linux (cron) — recomendado

Abre el crontab del usuario que corre la aplicación (por ejemplo el usuario `www-data` o `deploy`) y añade la siguiente línea (se ejecuta cada minuto y Laravel decide qué tareas ejecutar según el schedule):

```cron
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

Sustituye `/ruta/al/proyecto` por la ruta real del proyecto en el servidor.

2) Linux (systemd + schedule:work) — alternativa

Si prefieres un worker persistente bajo systemd, crea un service unit que ejecute `php artisan schedule:work` y configúralo con `systemctl` y `journalctl` para supervisión.

Ejemplo (archivo `/etc/systemd/system/laravel-scheduler.service`):

```ini
[Unit]
Description=Laravel Schedule Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
WorkingDirectory=/ruta/al/proyecto
ExecStart=/usr/bin/php /ruta/al/proyecto/artisan schedule:work

[Install]
WantedBy=multi-user.target
```

Luego:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now laravel-scheduler.service
```

3) Windows Server (Task Scheduler)

Crear una tarea programada que ejecute cada minuto (o cada 5 minutos) el comando:

```powershell
cd C:\ruta\al\proyecto
php artisan schedule:run
```

4) Comprobaciones y pruebas

- Ejecutar manualmente el comando para probar:

```bash
php artisan invoices:mark-overdue
# o para disparar el scheduler manualmente:
php artisan schedule:run
```

- Comprobar facturas vencidas hoy desde tinker:

```bash
php artisan tinker --execute "use App\\Models\\FacturaModelo; use Carbon\\Carbon; echo FacturaModelo::where('estado','vencida')->whereDate('fecha_vencimiento', Carbon::today())->count();"
```

Notas:
- El comando modifica la base de datos directamente; para que todos los usuarios vean los cambios debe ejecutarse en la instancia que comparte la base de datos (producción).
- Si necesitas, puedo añadir logging/auditoría al comando para registrar cuántas facturas se marcaron y cuándo (recomendado para producción).
