<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Mi Tienda Online API",
 *     description="API REST para gestión de tienda Vue + Laravel",
 *     @OA\Contact(
 *         email="admin@tienda.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum Token"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests;
}