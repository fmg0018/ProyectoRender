<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nWebhookService
{
    /**
     * Envía una notificación a n8n cuando se crea una factura
     *
     * @param object $factura Modelo de la factura
     * @param object $cliente Modelo del cliente
     * @return bool
     */
    public static function notificarNuevaFactura($factura, $cliente)
    {
        $webhookUrl = config('n8n.webhook_url');

        Log::info('🔔 N8nWebhookService::notificarNuevaFactura llamado', [
            'webhook_url' => $webhookUrl ? 'CONFIGURADA' : 'NO CONFIGURADA',
            'webhook_url_value' => $webhookUrl,
            'factura_id' => $factura->id,
            'cliente_id' => $cliente->id,
            'cliente_email' => $cliente->email,
            'cliente_nombre' => $cliente->nombre
        ]);

        // Si no hay URL configurada, no hacer nada
        if (empty($webhookUrl)) {
            Log::warning('❌ N8N_WEBHOOK_URL no está configurada en .env');
            return false;
        }

        try {
            // Preparar datos para enviar a n8n - asegurar que todos los valores existan
            $payload = [
                'evento' => 'nueva_factura',
                'factura' => [
                    'id' => (int) $factura->id,
                    'numero_factura' => (string) $factura->numero_factura,
                    'fecha_emision' => $factura->fecha_emision ? $factura->fecha_emision->format('Y-m-d') : date('Y-m-d'),
                    'fecha_vencimiento' => $factura->fecha_vencimiento ? $factura->fecha_vencimiento->format('Y-m-d') : date('Y-m-d'),
                    'subtotal' => (float) ($factura->subtotal ?? 0),
                    'impuestos' => (float) ($factura->impuestos ?? 0),
                    'total' => (float) ($factura->total ?? 0),
                    'estado' => (string) ($factura->estado ?? 'pendiente'),
                    'descripcion' => (string) ($factura->descripcion ?? ''),
                ],
                'cliente' => [
                    'id' => (int) $cliente->id,
                    'nombre' => (string) ($cliente->nombre ?? 'Sin nombre'),
                    'email' => (string) ($cliente->email ?? ''),
                    'telefono' => (string) ($cliente->telefono ?? ''),
                    'empresa' => (string) ($cliente->empresa ?? ''),
                ]
            ];

            Log::info('📤 Enviando payload a n8n', [
                'url' => $webhookUrl,
                'payload' => json_encode($payload)
            ]);

            // Enviar petición POST al webhook de n8n con timeout mayor
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($webhookUrl, $payload);

            Log::info('📥 Respuesta de n8n recibida', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                Log::info('✅ Notificación enviada a n8n correctamente', [
                    'factura_id' => $factura->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return true;
            } else {
                Log::error('❌ Error al enviar notificación a n8n', [
                    'factura_id' => $factura->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'url' => $webhookUrl
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('💥 Excepción al enviar notificación a n8n', [
                'factura_id' => $factura->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'webhook_url' => $webhookUrl
            ]);
            return false;
        }
    }

    /**
     * Envía una notificación a n8n cuando se crea una incidencia
     *
     * @param object $incidencia Modelo de la incidencia
     * @param object $cliente Modelo del cliente
     * @param object $responsable Modelo del usuario responsable
     * @return bool
     */
    public static function notificarNuevaIncidencia($incidencia, $cliente, $responsable)
    {
        $webhookUrl = config('n8n.webhook_url_incidencia');

        Log::info('🔔 N8nWebhookService::notificarNuevaIncidencia llamado', [
            'webhook_url' => $webhookUrl ? 'CONFIGURADA' : 'NO CONFIGURADA',
            'webhook_url_value' => $webhookUrl,
            'incidencia_id' => $incidencia->id,
            'cliente_id' => $cliente->id,
            'cliente_email' => $cliente->email,
            'cliente_nombre' => $cliente->nombre
        ]);

        // Si no hay URL configurada, no hacer nada
        if (empty($webhookUrl)) {
            Log::warning('❌ N8N_WEBHOOK_URL_INCIDENCIA no está configurada');
            return false;
        }

        try {
            // Preparar datos para enviar a n8n
            $payload = [
                'evento' => 'nueva_incidencia',
                'incidencia' => [
                    'id' => (int) $incidencia->id,
                    'titulo' => (string) $incidencia->titulo,
                    'descripcion' => (string) ($incidencia->descripcion ?? ''),
                    'prioridad' => (string) ($incidencia->prioridad ?? 'media'),
                    'estado' => (string) ($incidencia->estado ?? 'abierta'),
                    'fecha_reporte' => $incidencia->fecha_reporte ?? date('Y-m-d'),
                    'fecha_resolucion' => $incidencia->fecha_resolucion ?? null,
                    'solucion' => (string) ($incidencia->solucion ?? ''),
                ],
                'cliente' => [
                    'id' => (int) $cliente->id,
                    'nombre' => (string) ($cliente->nombre ?? 'Sin nombre'),
                    'email' => (string) ($cliente->email ?? ''),
                    'telefono' => (string) ($cliente->telefono ?? ''),
                    'empresa' => (string) ($cliente->empresa ?? ''),
                ],
                'responsable' => [
                    'id' => (int) $responsable->id,
                    'nombre' => (string) ($responsable->name ?? 'Sin nombre'),
                    'email' => (string) ($responsable->email ?? ''),
                ]
            ];

            Log::info('📤 Enviando incidencia a n8n', [
                'url' => $webhookUrl,
                'payload' => json_encode($payload)
            ]);

            // Enviar petición POST al webhook de n8n
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($webhookUrl, $payload);

            Log::info('📥 Respuesta de n8n recibida', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                Log::info('✅ Notificación de incidencia enviada a n8n correctamente', [
                    'incidencia_id' => $incidencia->id,
                    'status' => $response->status()
                ]);
                return true;
            } else {
                Log::error('❌ Error al enviar notificación de incidencia a n8n', [
                    'incidencia_id' => $incidencia->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('💥 Excepción al enviar notificación de incidencia a n8n', [
                'incidencia_id' => $incidencia->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }
}
