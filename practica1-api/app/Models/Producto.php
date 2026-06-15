<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'categoria_id',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->when($termino, function ($q) use ($termino) {
            $q->where(function ($subquery) use ($termino) {
                $subquery->where('nombre', 'LIKE', "%{$termino}%")
                         ->orWhere('descripcion', 'LIKE', "%{$termino}%");
            });
        });
    }

    public function scopeDeCategoria($query, $categoriaId)
    {
        return $query->when($categoriaId, function ($q) use ($categoriaId) {
            $q->where('categoria_id', $categoriaId);
        });
    }

    public function scopeRangoPrecio($query, $min, $max)
    {
        return $query
            ->when($min, function ($q) use ($min) {
                $q->where('precio', '>=', $min);
            })
            ->when($max, function ($q) use ($max) {
                $q->where('precio', '<=', $max);
            });
    }
}