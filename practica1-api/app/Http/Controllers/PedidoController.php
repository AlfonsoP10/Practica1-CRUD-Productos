<?php

namespace App\Http\Controllers;

use App\Events\NuevoPedidoRecibido;
use App\Events\StockBajoAlerta;
use App\Jobs\EnviarConfirmacionPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class PedidoController extends Controller
{   
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        $pedido = DB::transaction(function () use ($request) {
            $total = 0;

            $pedido = Pedido::create([
                'user_id' => $request->user()->getKey(),
                'total' => 0,
                'estado' => 'procesando',
            ]);

            foreach ($request->items as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                $cantidad = $item['cantidad'];
                $precioUnitario = $producto->precio;

                $pedido->items()->create([
                    'producto_id' => $producto->getKey(),
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                ]);

                $producto->decrement('stock', $cantidad);
                $producto->refresh();

                if ($producto->stock <= 5) {
                    broadcast(new StockBajoAlerta($producto, $producto->stock));
                }

                $total += $precioUnitario * $cantidad;
            }

            $pedido->update([
                'total' => $total,
            ]);

            return $pedido;
        });

        $pedido->load(['user', 'items']);

        broadcast(new NuevoPedidoRecibido($pedido))->toOthers();

        EnviarConfirmacionPedido::dispatch($pedido)->delay(now()->addSeconds(5));

        return response()->json([
            'message' => 'Pedido creado correctamente',
            'pedido_id' => $pedido->getKey(),
        ], 201);
    }

    public function show(Request $request, Pedido $pedido)
    {
        if ($pedido->user_id !== $request->user()->getKey()) {
            abort(403, 'No puedes ver este pedido');
        }

        $pedido->load('items.producto');

        return response()->json([
            'id' => $pedido->getKey(),
            'total' => $pedido->total,
            'estado' => $pedido->estado,
            'email_enviado_at' => $pedido->email_enviado_at,
            'items' => $pedido->items,
        ]);
    }
}