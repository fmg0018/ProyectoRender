<?php

return [
    /*
    |--------------------------------------------------------------------------
    | N8N Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | URLs de webhooks de n8n para enviar notificaciones de eventos
    | del sistema (creación de facturas, incidencias, etc.)
    |
    */
    'webhook_url' => env('N8N_WEBHOOK_URL'),
    'webhook_url_incidencia' => env('N8N_WEBHOOK_URL_INCIDENCIA'),
];
