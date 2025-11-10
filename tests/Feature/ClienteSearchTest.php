<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ClienteModelo;

class ClienteSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_devuelve_resultados()
    {
        ClienteModelo::create(['nombre' => 'Juan Perez', 'email' => 'juan@example.com']);
        ClienteModelo::create(['nombre' => 'Ana Lopez', 'email' => 'ana@example.com']);

        $response = $this->getJson(route('clientes.search', ['q' => 'Juan']));
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['nombre' => 'Juan Perez']);
    }
}
