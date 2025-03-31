<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas protegidas por Sanctum (usuarios autenticados)
Route::middleware('auth:sanctum')->group(function () {
    // Obtener información del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user(); // Devuelve el usuario autenticado
    });

    // Rutas protegidas para gestionar pedidos y productos
    Route::apiResource('/pedidos', PedidoController::class);
    Route::apiResource('/productos', ProductoController::class);
});

// Recursos API abiertos (no requieren autenticación)
Route::apiResource('/categorias', CategoriaController::class);

// Rutas públicas para registro e inicio de sesión
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
