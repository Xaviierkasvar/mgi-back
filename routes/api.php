<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

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

// Ruta para obtener el token JWT (pública)
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas que requieren autenticación JWT
Route::middleware('jwt.auth')->group(function () {
    // Rutas del CRUD de productos
    Route::apiResource('products', ProductController::class);

    // Ruta adicional para actualizar el stock
    Route::put('/products/{id}/stock', [ProductController::class, 'updateStock']);

    // Ruta para obtener el usuario autenticado
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});

// Ejemplo de ruta protegida adicional
Route::middleware('jwt.auth')->get('/secure-data', function () {
    return response()->json(['message' => 'Esta ruta está protegida por JWT']);
});
