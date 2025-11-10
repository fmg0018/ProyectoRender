<?php

namespace Database\Seeders;

use App\Models\ClienteModelo;
use Illuminate\Database\Seeder;

class ClienteDePruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClienteModelo::firstOrCreate(
            ['email' => 'sergio.mira@example.com'],
            [
                'nombre' => 'Sergio Mira Jover',
                'telefono' => '+34 600 123 456',
                'empresa' => 'Zaitec',
                'direccion' => 'Calle Innovación 42',
                'ciudad' => 'Alicante',
                'pais' => 'España',
                'estado' => 'activo',
                'notas' => 'Cliente de pruebas para incidencias.',
            ]
        );
    }
}
