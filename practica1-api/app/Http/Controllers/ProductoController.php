<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class ProductoController extends Controller
{
        #[OA\Get(
        path: '/api/v1/productos',
        tags: ['Productos'],
        summary: 'Listar productos',
        description: 'Obtiene una lista paginada de productos con filtros opcionales.',
        parameters: [
            new OA\Parameter(
                name: 'busqueda',
                in: 'query',
                required: false,
                description: 'Texto para buscar por nombre o descripción',
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'categoria_id',
                in: 'query',
                required: false,
                description: 'Filtrar por categoría',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'precio_min',
                in: 'query',
                required: false,
                description: 'Precio mínimo',
                schema: new OA\Schema(type: 'number')
            ),
            new OA\Parameter(
                name: 'precio_max',
                in: 'query',
                required: false,
                description: 'Precio máximo',
                schema: new OA\Schema(type: 'number')
            ),
            new OA\Parameter(
                name: 'por_pagina',
                in: 'query',
                required: false,
                description: 'Cantidad de productos por página',
                schema: new OA\Schema(type: 'integer', example: 15)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista de productos obtenida correctamente')
        ]
    )]
    public function index()
    {
        $request = request();

        $ordenPermitido = ['nombre', 'precio', 'stock', 'created_at'];
        $orden = $request->get('orden', 'nombre');
        $dir = $request->get('dir', 'asc');

        if (!in_array($orden, $ordenPermitido)) {
            $orden = 'nombre';
        }

        if (!in_array($dir, ['asc', 'desc'])) {
            $dir = 'asc';
        }

        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($orden, $dir)
            ->paginate($request->get('por_pagina', 15));

        $productos->getCollection()->transform(function ($producto) {
            $producto->imagen_url = $producto->imagen
                ? asset('storage/' . $producto->imagen)
                : null;

            return $producto;
        });

        return response()->json($productos);
    }
    
    #[OA\Post(
    path: '/api/v1/productos',
    tags: ['Productos'],
    summary: 'Crear producto',
    description: 'Crea un nuevo producto.',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['nombre', 'precio', 'stock', 'categoria_id'],
                properties: [
                    new OA\Property(
                        property: 'nombre',
                        type: 'string',
                        example: 'Laptop Lenovo'
                    ),
                    new OA\Property(
                        property: 'descripcion',
                        type: 'string',
                        example: 'Laptop para oficina'
                    ),
                    new OA\Property(
                        property: 'precio',
                        type: 'number',
                        format: 'float',
                        example: 15999.99
                    ),
                    new OA\Property(
                        property: 'stock',
                        type: 'integer',
                        example: 10
                    ),
                    new OA\Property(
                        property: 'categoria_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'imagen',
                        type: 'string',
                        format: 'binary'
                    )
                ]
            )
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Producto creado correctamente'
        ),
        new OA\Response(
            response: 403,
            description: 'No autorizado'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    public function store(StoreProductoRequest $request)
    {
        $this->authorize('create', Producto::class);

        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);

        $producto->load('categoria');

        $producto->imagen_url = $producto->imagen
            ? asset('storage/' . $producto->imagen)
            : null;

        return response()->json($producto, 201);
    }

    #[OA\Get(
    path: '/api/v1/productos/{producto}',
    tags: ['Productos'],
    summary: 'Obtener producto',
    description: 'Obtiene un producto por su ID.',
    parameters: [
        new OA\Parameter(
            name: 'producto',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Producto encontrado'
        ),
        new OA\Response(
            response: 404,
            description: 'Producto no encontrado'
        )
    ]
)]
    public function show(Producto $producto)
    {
        $producto->load('categoria');

        $producto->imagen_url = $producto->imagen
            ? asset('storage/' . $producto->imagen)
            : null;

        return response()->json($producto, 200);
    }
    #[OA\Put(
    path: '/api/v1/productos/{producto}',
    tags: ['Productos'],
    summary: 'Actualizar producto',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'producto',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(type: 'integer', example: 1)
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Laptop actualizada'),
                    new OA\Property(property: 'descripcion', type: 'string', example: 'Nueva descripción'),
                    new OA\Property(property: 'precio', type: 'number', format: 'float', example: 16999.99),
                    new OA\Property(property: 'stock', type: 'integer', example: 8),
                    new OA\Property(property: 'categoria_id', type: 'integer', example: 1),
                    new OA\Property(property: 'imagen', type: 'string', format: 'binary')
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Producto actualizado correctamente'),
        new OA\Response(response: 403, description: 'No autorizado'),
        new OA\Response(response: 404, description: 'Producto no encontrado'),
        new OA\Response(response: 422, description: 'Error de validación')
    ]
)]
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->authorize('update', $producto);

        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);

        $producto->load('categoria');

        $producto->imagen_url = $producto->imagen
            ? asset('storage/' . $producto->imagen)
            : null;

        return response()->json($producto, 200);
    }
    #[OA\Delete(
    path: '/api/v1/productos/{producto}',
    tags: ['Productos'],
    summary: 'Eliminar producto',
    description: 'Elimina un producto por su ID.',
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'producto',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(type: 'integer', example: 1)
        )
    ],
    responses: [
        new OA\Response(response: 204, description: 'Producto eliminado correctamente'),
        new OA\Response(response: 403, description: 'No autorizado'),
        new OA\Response(response: 404, description: 'Producto no encontrado')
    ]
)]
    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return response()->json(null, 204);
    }
}