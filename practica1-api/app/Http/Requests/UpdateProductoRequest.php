<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'       => 'sometimes|required|string|min:3|max:100',
            'descripcion'  => 'nullable|string|max:500',
            'precio'       => 'sometimes|required|numeric|min:0.01|max:99999',
            'stock'        => 'sometimes|required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos :min caracteres.',
            'nombre.max' => 'El nombre no puede tener más de :max caracteres.',

            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede superar :max caracteres.',

            'precio.required' => 'Debes indicar un precio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor a cero.',
            'precio.max' => 'El precio no puede superar :max.',

            'stock.required' => 'Debes indicar el stock.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',

            'categoria_id.exists' => 'La categoría seleccionada no existe.',

            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG o WEBP.',
            'imagen.max' => 'La imagen no puede superar 2 MB.',
        ];
    }
}