<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ClienteModelo;
use App\Models\FacturaModelo;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaEnviada;

class EnviarFacturaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_trigger_send_invoice_email()
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $cliente = ClienteModelo::create(['nombre' => 'C', 'email' => 'c@example.com']);

        $factura = FacturaModelo::create([
            'numero_factura' => 'FCT-2025-0001',
            'cliente_id' => $cliente->id,
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(30),
            'subtotal' => 10,
            'impuestos' => 1,
            'total' => 11,
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($admin)->post(route('facturas.enviar', $factura->id));

        $response->assertRedirect();

        Mail::assertSent(FacturaEnviada::class, function ($mail) use ($cliente) {
            return $mail->hasTo($cliente->email);
        });
    }
}
