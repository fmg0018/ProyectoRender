<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\MarkOverdueInvoices;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        MarkOverdueInvoices::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
    // Ejecutar una vez al día para marcar facturas vencidas
    $schedule->command('invoices:mark-overdue')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // cargar comandos por defecto si los hay
        $this->load(__DIR__ . '/Commands');
    }
}
