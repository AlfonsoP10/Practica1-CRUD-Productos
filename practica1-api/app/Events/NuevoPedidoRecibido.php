<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class NuevoPedidoRecibido implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pedido $pedido
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-panel'),
        ];
    }

    public function broadcastWith(): array
    {
        $this->pedido->loadMissing(['user', 'items']);

        return [
            'id' => $this->pedido->id,
            'total' => $this->pedido->total,
            'cliente' => $this->pedido->user?->name ?? 'Cliente',
            'items' => $this->pedido->items->count(),
            'created_at' => $this->pedido->created_at?->format('H:i:s'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NuevoPedidoRecibido';
    }
}