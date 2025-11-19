<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nWebhookService
{
    /**
     * Envía una notificación a n8n cuando se crea una factura
     *
     * @param array $data Datos de la factura y el cliente
     * @return bool
     */
    public static function notificarNuevaFactura($factura, $cliente)
    {
        $webhookUrl = env('N8N_WEBHOOK_URL');

        Log::info('🔔 N8nWebhookService::notificarNuevaFactura llamado', [
            'webhook_url' => $webhookUrl,
            'factura_id' => $factura->id,
            'cliente_email' => $cliente->email
        ]);

        // Si no hay URL configurada, no hacer nada
        if (empty($webhookUrl)) {
            Log::warning('❌ N8N_WEBHOOK_URL no está configurada');
            return false;
        }

        try {
            // Preparar datos para enviar a n8n
            $payload = [
                'evento' => 'nueva_factura',
                'factura' => [
                    'id' => $factura->id,
                    'numero_factura' => $factura->numero_factura,
                    'fecha_emision' => $factura->fecha_emision->format('Y-m-d'),
                    'fecha_vencimiento' => $factura->fecha_vencimiento->format('Y-m-d'),
                    'subtotal' => $factura->subtotal,
                    'impuestos' => $factura->impuestos,
                    'total' => $factura->total,
                    'estado' => $factura->estado,
                    'descripcion' => $factura->descripcion,
                ],
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'email' => $cliente->email,
                    'telefono' => $cliente->telefono,
                    'empresa' => $cliente->empresa,
                ]
            ];

            Log::info('📤 Enviando payload a n8n', ['url' => $webhookUrl]);

            // Enviar petición POST al webhook de n8n
            $response = Http::timeout(5)->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('✅ Notificación enviada a n8n correctamente', [
                    'factura_id' => $factura->id,
                    'status' => $response->status()
                ]);
                return true;
            } else {
                Log::error('❌ Error al enviar notificación a n8n', [
                    'factura_id' => $factura->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('💥 Excepción al enviar notificación a n8n', [
                'factura_id' => $factura->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
