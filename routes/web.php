<?php

use App\Http\Controllers\ClienteControlador;
use App\Http\Controllers\DashboardControlador;
use App\Http\Controllers\FacturaControlador;
use App\Http\Controllers\IncidenciaControlador;
use App\Http\Controllers\InicioControlador;
use App\Http\Controllers\LoginControlador;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioControlador::class, 'index'])->name('inicio');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginControlador::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginControlador::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginControlador::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardControlador::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('clientes/search', [ClienteControlador::class, 'search'])->name('clientes.search');
    Route::get('clientes/{cliente}/facturas', [ClienteControlador::class, 'facturas'])->name('clientes.facturas');
    Route::get('clientes/{cliente}/incidencias', [ClienteControlador::class, 'incidencias'])->name('clientes.incidencias');
    Route::resource('clientes', ClienteControlador::class);

    Route::resource('facturas', FacturaControlador::class);
    Route::get('facturas/{factura}/pdf', [FacturaControlador::class, 'downloadPdf'])->name('facturas.pdf');
    Route::post('facturas/{factura}/enviar', [FacturaControlador::class, 'enviarPorEmail'])->name('facturas.enviar');

    Route::resource('incidencias', IncidenciaControlador::class);
});

require __DIR__.'/auth.php';
