<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Cache::remember('categorias.todas', 3600, function () {
            return CategoriaResource::collection(
                Categoria::with('productos')->get()
            )->toArray(request());
        });

        return response()->json([
            'data' => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $categoria = Categoria::create([
            'nombre' => $request->nombre,
            'slug' => $request->slug,
            'descripcion' => $request->descripcion,
        ]);

        Cache::forget('categorias.todas');

        return new CategoriaResource($categoria);
    }

    public function show(Categoria $categoria)
    {
        return new CategoriaResource(
            $categoria->load('productos')
        );
    }

    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($request->all());

        Cache::forget('categorias.todas');

        return new CategoriaResource($categoria);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        Cache::forget('categorias.todas');

        return response()->json([
            'message' => 'Categoría eliminada'
        ]);
    }

    public function productos(Categoria $categoria)
    {
        return ProductoResource::collection(
            $categoria->productos()
                      ->with('categoria')
                      ->get()
        );
    }
}