<?php

use App\Http\Controllers\Api\ClienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas para Clientes (con sus Mascotas)
Route::prefix('clientes')->name('clientes.')->group(function () {
    Route::get('/', [ClienteController::class, 'index'])->name('index');
    Route::get('/{id}', [ClienteController::class, 'show'])->name('show');
    Route::post('/', [ClienteController::class, 'store'])->name('store');
    Route::put('/{id}', [ClienteController::class, 'update'])->name('update');
    Route::delete('/{id}', [ClienteController::class, 'destroy'])->name('destroy');
});

