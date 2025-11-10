<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacturasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\FacturaModelo::create([
            'numero_factura' => 'FAC-2025-001',
            'cliente_id' => 1,
            'fecha_emision' => '2025-10-01',
            'fecha_vencimiento' => '2025-10-31',
            'subtotal' => 1200.00,
            'impuestos' => 252.00,
            'total' => 1452.00,
            'estado' => 'pagada',
            'descripcion' => 'Desarrollo de aplicación web personalizada'
        ]);

        \App\Models\FacturaModelo::create([
            'numero_factura' => 'FAC-2025-002',
            'cliente_id' => 2,
            'fecha_emision' => '2025-10-15',
            'fecha_vencimiento' => '2025-11-15',
            'subtotal' => 800.00,
            'impuestos' => 168.00,
            'total' => 968.00,
            'estado' => 'pendiente',
            'descripcion' => 'Consultoría en transformación digital'
        ]);

        \App\Models\FacturaModelo::create([
            'numero_factura' => 'FAC-2025-003',
            'cliente_id' => 1,
            'fecha_emision' => '2025-09-20',
            'fecha_vencimiento' => '2025-10-20',
            'subtotal' => 2500.00,
            'impuestos' => 525.00,
            'total' => 3025.00,
            'estado' => 'pagada',
            'descripcion' => 'Implementación de sistema CRM completo'
        ]);

        \App\Models\FacturaModelo::create([
            'numero_factura' => 'FAC-2025-004',
            'cliente_id' => 4,
            'fecha_emision' => '2025-10-10',
            'fecha_vencimiento' => '2025-11-10',
            'subtotal' => 1500.00,
            'impuestos' => 315.00,
            'total' => 1815.00,
            'estado' => 'pendiente',
            'descripcion' => 'Auditoría de seguridad informática'
        ]);

        \App\Models\FacturaModelo::create([
            'numero_factura' => 'FAC-2025-005',
            'cliente_id' => 2,
            'fecha_emision' => '2025-09-05',
            'fecha_vencimiento' => '2025-10-05',
            'subtotal' => 650.00,
            'impuestos' => 136.50,
            'total' => 786.50,
            'estado' => 'pagada',
            'descripcion' => 'Mantenimiento mensual de infraestructura'
        ]);
    }
}
