<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// La raíz redirige directamente al login
// (si ya hay sesión, el middleware 'guest' de /login envía al dashboard).
Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================
    // Rutas del Sistema de Inventario
    // =========================================================
    Route::patch('/settings/bcv-rate', [DashboardController::class, 'updateBcvRate'])->name('settings.bcv-rate');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::resource('inventory-movements', InventoryMovementController::class)->only(['index', 'store']);
});

require __DIR__.'/auth.php';
