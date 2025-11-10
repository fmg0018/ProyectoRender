<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ClienteModelo::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@email.com',
            'telefono' => '+34 600 123 456',
            'empresa' => 'Tech Solutions SL',
            'direccion' => 'Calle Mayor 123',
            'ciudad' => 'Madrid',
            'pais' => 'España',
            'estado' => 'activo',
            'notas' => 'Cliente preferente con alta facturación'
        ]);

        \App\Models\ClienteModelo::create([
            'nombre' => 'María García',
            'email' => 'maria.garcia@empresa.com',
            'telefono' => '+34 655 987 654',
            'empresa' => 'Innovación Digital',
            'direccion' => 'Avenida de la Paz 45',
            'ciudad' => 'Barcelona',
            'pais' => 'España',
            'estado' => 'activo',
            'notas' => 'Nuevo cliente con potencial de crecimiento'
        ]);

        \App\Models\ClienteModelo::create([
            'nombre' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@startup.es',
            'telefono' => '+34 612 345 789',
            'empresa' => 'StartUp Innovations',
            'direccion' => 'Plaza del Sol 8',
            'ciudad' => 'Valencia',
            'pais' => 'España',
            'estado' => 'pendiente',
            'notas' => 'En proceso de validación de documentos'
        ]);

        \App\Models\ClienteModelo::create([
            'nombre' => 'Ana López',
            'email' => 'ana.lopez@consultoria.com',
            'telefono' => '+34 678 901 234',
            'empresa' => 'Consultoría Estratégica',
            'direccion' => 'Calle Serrano 67',
            'ciudad' => 'Madrid',
            'pais' => 'España',
            'estado' => 'activo',
            'notas' => 'Cliente de larga duración, muy satisfecho con el servicio'
        ]);

        \App\Models\ClienteModelo::create([
            'nombre' => 'David Martín',
            'email' => 'david.martin@freelance.com',
            'telefono' => '+34 634 567 890',
            'empresa' => 'Freelance Developer',
            'direccion' => 'Calle Gran Vía 123',
            'ciudad' => 'Sevilla',
            'pais' => 'España',
            'estado' => 'inactivo',
            'notas' => 'Cliente inactivo desde el mes pasado'
        ]);
    }
}
