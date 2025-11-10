<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FacturaModelo;
use Carbon\Carbon;

class MarkOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca como vencidas las facturas cuya fecha de vencimiento ya pasó o es hoy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $today = Carbon::now()->startOfDay();

        // Excluir facturas que ya estén marcadas como "vencida" o que ya hayan sido "pagada"
        $query = FacturaModelo::whereNotIn('estado', ['vencida', 'pagada'])
            ->whereDate('fecha_vencimiento', '<=', $today);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No hay facturas pendientes de marcar como vencidas.');
            return 0;
        }

        // Actualizamos en chunk para evitar sobrecargar la memoria si hay muchas facturas
        $updated = 0;
        $query->chunkById(100, function ($facturas) use (&$updated) {
            foreach ($facturas as $factura) {
                $factura->estado = 'vencida';
                $factura->save();
                $updated++;
            }
        });

        $this->info("Facturas marcadas como vencidas: $updated");
        return 0;
    }
}
