<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ClienteModelo;

class FacturaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_facturas_index()
    {
        // Crear usuario admin
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('facturas.index'));

        $response->assertStatus(200);
        $response->assertSee('Lista de Facturas');
    }

    public function test_admin_can_create_factura()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Crear cliente
        $cliente = ClienteModelo::create([
            'nombre' => 'Cliente Test',
            'email' => 'cliente@test.com',
        ]);

        $data = [
            'cliente_id' => $cliente->id,
            'fecha_emision' => now()->format('Y-m-d'),
            'fecha_vencimiento' => now()->addDays(30)->format('Y-m-d'),
            'subtotal' => '100.00',
            'impuestos' => '21.00',
            'descripcion' => 'Factura de prueba',
            'estado' => 'pendiente',
        ];

        $response = $this->actingAs($admin)->post(route('facturas.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('facturas', [
            'cliente_id' => $cliente->id,
            'subtotal' => '100.00',
            'total' => '121.00',
        ]);
    }
}
