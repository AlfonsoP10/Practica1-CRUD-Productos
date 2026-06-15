<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(2, true),
            'descripcion' => fake()->sentence(),
            'precio' => fake()->randomFloat(2, 50, 5000),
            'stock' => fake()->numberBetween(1, 100),
            'imagen' => null,
            'categoria_id' => Categoria::factory(),
        ];
    }
}