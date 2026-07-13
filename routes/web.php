<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoletaController;
use App\Http\Controllers\ReporteInventarioController;

Route::redirect('/', '/admin');
Route::get('/reporte/inventario/descargar', [ReporteInventarioController::class, 'descargarReporte'])->name('reporte.inventario.descargar');
Route::get('/boleta/descargar/{id}', [BoletaController::class, 'descargarBoleta'])->name('boleta.descargar');
Route::get('/login', [App\Filament\Pages\Auth\CustomLogin::class, '__invoke'])->name('login');