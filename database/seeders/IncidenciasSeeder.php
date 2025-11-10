<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario si no existe
        $user = \App\Models\User::firstOrCreate([
            'email' => 'admin@crm.com'
        ], [
            'name' => 'Admin Usuario',
            'password' => bcrypt('password123')
        ]);

        \App\Models\IncidenciaModelo::create([
            'titulo' => 'Error en el sistema de facturación',
            'descripcion' => 'El cliente reporta que no puede generar facturas desde el módulo de facturación. Error 500 al intentar guardar.',
            'cliente_id' => 1,
            'user_id' => $user->id,
            'prioridad' => 'alta',
            'estado' => 'abierta',
            'fecha_reporte' => '2025-10-18',
            'fecha_resolucion' => null,
            'solucion' => null
        ]);

        \App\Models\IncidenciaModelo::create([
            'titulo' => 'Lentitud en la carga de datos',
            'descripcion' => 'Los reportes del dashboard tardan más de 30 segundos en cargar. Afecta la productividad del equipo.',
            'cliente_id' => 2,
            'user_id' => $user->id,
            'prioridad' => 'media',
            'estado' => 'en_proceso',
            'fecha_reporte' => '2025-10-17',
            'fecha_resolucion' => null,
            'solucion' => 'Se está optimizando la consulta a la base de datos'
        ]);

        \App\Models\IncidenciaModelo::create([
            'titulo' => 'Problema con notificaciones por email',
            'descripcion' => 'Las notificaciones automáticas de vencimiento de facturas no se están enviando correctamente.',
            'cliente_id' => 4,
            'user_id' => $user->id,
            'prioridad' => 'media',
            'estado' => 'resuelta',
            'fecha_reporte' => '2025-10-15',
            'fecha_resolucion' => '2025-10-16',
            'solucion' => 'Configuración SMTP actualizada y servicio de emails restablecido'
        ]);

        \App\Models\IncidenciaModelo::create([
            'titulo' => 'Solicitud de nueva funcionalidad',
            'descripcion' => 'El cliente solicita añadir un módulo de inventario integrado con el sistema actual.',
            'cliente_id' => 1,
            'user_id' => $user->id,
            'prioridad' => 'baja',
            'estado' => 'abierta',
            'fecha_reporte' => '2025-10-19',
            'fecha_resolucion' => null,
            'solucion' => null
        ]);

        \App\Models\IncidenciaModelo::create([
            'titulo' => 'Error de permisos de usuario',
            'descripcion' => 'Algunos usuarios no pueden acceder al módulo de reportes avanzados.',
            'cliente_id' => 3,
            'user_id' => $user->id,
            'prioridad' => 'alta',
            'estado' => 'en_proceso',
            'fecha_reporte' => '2025-10-18',
            'fecha_resolucion' => null,
            'solucion' => 'Revisando configuración de roles y permisos'
        ]);
    }
}
