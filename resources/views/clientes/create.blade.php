<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioControlador;
use App\Http\Controllers\ClienteController;

// Ruta para la página de inicio
Route::get('/', [InicioControlador::class, 'index']);

// Rutas para la gestión de clientes
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/crear', [ClienteController::class, 'create'])->name('clientes.create'); 
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store'); 
