<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permiso:gestionar_usuarios')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{id}/actualizar-accesos', [UserController::class, 'updateAccesos']);
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });

    Route::middleware('permiso:ver_inventario')->group(function () {
        Route::get('/inventario', [ProductController::class, 'index'])->name('inventario.index');
        Route::get('/inventario/exportar-pdf', [ProductController::class, 'exportarPdf'])->name('inventario.pdf');
        
        Route::middleware('permiso:editar_inventario')->group(function () {
            Route::post('/inventario', [ProductController::class, 'store'])->name('inventario.store');
            Route::put('/inventario/{id}', [ProductController::class, 'update'])->name('inventario.update');
            Route::delete('/inventario/{id}', [ProductController::class, 'destroy'])->name('inventario.destroy');
        });
    });

    Route::middleware('permiso:procesar_ventas')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/venta', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/boleta/{id}/pdf', [PosController::class, 'exportarPdf'])->name('pos.pdf');
    });

    Route::middleware('permiso:gestionar_compras')->group(function () {
        Route::get('/compras', [PurchaseOrderController::class, 'index'])->name('compras.index');
        Route::post('/compras', [PurchaseOrderController::class, 'store'])->name('compras.store');
        Route::put('/compras/{id}', [PurchaseOrderController::class, 'update'])->name('compras.update');
        Route::delete('/compras/{id}', [PurchaseOrderController::class, 'destroy'])->name('compras.destroy');
        Route::get('/compras/{id}/pdf', [PurchaseOrderController::class, 'exportarPdf'])->name('compras.pdf');
        Route::post('/proveedores', [PurchaseOrderController::class, 'storeProveedor'])->name('proveedores.store');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('/', function () {
    return redirect()->route('login');
});