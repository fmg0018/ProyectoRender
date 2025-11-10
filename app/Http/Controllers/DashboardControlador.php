<?php

namespace App\Http\Controllers;

use App\Models\ClienteModelo;
use App\Models\FacturaModelo;
use App\Models\IncidenciaModelo;
use App\Models\User;

class DashboardControlador extends Controller
{
    public function index()
    {
        $totalClientes = ClienteModelo::count();
        $clientesActivos = ClienteModelo::where('estado', 'activo')->count();

        $totalFacturas = FacturaModelo::count();
        $facturasPagadas = FacturaModelo::where('estado', 'pagada')->count();

        $hoy = now();

        // Facturas vencidas: estado marcado como vencida o pendientes cuyo vencimiento ya pasó
        $facturasVencidas = FacturaModelo::where(function ($query) use ($hoy) {
            $query->where('estado', 'vencida')
                  ->orWhere(function ($sub) use ($hoy) {
                      $sub->where('estado', 'pendiente')
                          ->whereNotNull('fecha_vencimiento')
                          ->where('fecha_vencimiento', '<', $hoy);
                  });
        })->count();

        // Facturas pendientes sin vencer: pendientes con fecha vencimiento futura o sin fecha definida
        $facturasPendientesSinVencer = FacturaModelo::where('estado', 'pendiente')
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_vencimiento')
                      ->orWhere('fecha_vencimiento', '>=', $hoy);
            })
            ->count();

        $facturasPendientes = $facturasPendientesSinVencer + $facturasVencidas;
        
        $ingresosTotales = FacturaModelo::where('estado', 'pagada')->sum('total');

        $totalIncidencias = IncidenciaModelo::count();
        $incidenciasAbiertas = IncidenciaModelo::where('estado', 'abierta')->count();
        $incidenciasResueltas = IncidenciaModelo::where('estado', 'resuelta')->count();

        $totalUsuarios = User::count();

        return view('dashboard.dashboardVista', compact(
            'totalClientes',
            'clientesActivos',
            'totalFacturas',
            'facturasPendientes',
            'facturasPendientesSinVencer',
            'facturasPagadas',
            'facturasVencidas',
            'ingresosTotales',
            'totalIncidencias',
            'incidenciasAbiertas',
            'incidenciasResueltas',
            'totalUsuarios'
        ));
    }
}
