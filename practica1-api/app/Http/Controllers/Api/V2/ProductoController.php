<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\ProductoController as V1ProductoController;
use App\Models\Producto;
use OpenApi\Attributes as OA;

class ProductoController extends V1ProductoController
{
       
    public function index()
    {
        $request = request();

        $query = Producto::with('categoria');

        if ($request->filled('q')) {
            $query->where(function ($consulta) use ($request) {
                $consulta->where('nombre', 'like', '%' . $request->q . '%')
                    ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        $productos = $query->paginate(20);

        $productos->getCollection()->transform(function ($producto) {
            $producto->imagen_url = $producto->imagen
                ? asset('storage/' . $producto->imagen)
                : null;

            return $producto;
        });

        return response()->json($productos);
    }
}