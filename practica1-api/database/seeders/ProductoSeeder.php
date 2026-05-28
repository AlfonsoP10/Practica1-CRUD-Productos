<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Laptop Lenovo',
            'descripcion' => 'Laptop para trabajo y escuela',
            'precio' => 12500.00,
            'stock' => 8,
        ]);

        Producto::create([
            'nombre' => 'Mouse Logitech',
            'descripcion' => 'Mouse inalámbrico',
            'precio' => 350.00,
            'stock' => 20,
        ]);

        Producto::create([
            'nombre' => 'Teclado Mecánico',
            'descripcion' => 'Teclado RGB para programación',
            'precio' => 950.00,
            'stock' => 12,
        ]);
    }
}