<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Api\V2\ProductoController as ProductoV2Controller;

/*
|--------------------------------------------------------------------------
| API V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('productos', [ProductoController::class, 'index']);
    Route::get('productos/{producto}', [ProductoController::class, 'show']);

    Route::get('categorias', [CategoriaController::class, 'index']);
    Route::get('categorias/{categoria}', [CategoriaController::class, 'show']);
    Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/broadcasting/auth', function (Request $request) {
            return Broadcast::auth($request);
        });

        Route::post('pedidos', [PedidoController::class, 'store']);
        Route::get('pedidos/{pedido}', [PedidoController::class, 'show']);

        Route::post('productos', [ProductoController::class, 'store']);
        Route::put('productos/{producto}', [ProductoController::class, 'update']);
        Route::patch('productos/{producto}', [ProductoController::class, 'update']);
        Route::delete('productos/{producto}', [ProductoController::class, 'destroy']);

        Route::post('categorias', [CategoriaController::class, 'store']);
        Route::put('categorias/{categoria}', [CategoriaController::class, 'update']);
        Route::patch('categorias/{categoria}', [CategoriaController::class, 'update']);
        Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy']);
    });

});

/*
|--------------------------------------------------------------------------
| API V2
|--------------------------------------------------------------------------
*/

Route::prefix('v2')->group(function () {

    Route::get('productos', [ProductoV2Controller::class, 'index']);
    Route::get('productos/{producto}', [ProductoV2Controller::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('productos', [ProductoV2Controller::class, 'store']);
        Route::put('productos/{producto}', [ProductoV2Controller::class, 'update']);
        Route::patch('productos/{producto}', [ProductoV2Controller::class, 'update']);
        Route::delete('productos/{producto}', [ProductoV2Controller::class, 'destroy']);
    });

});