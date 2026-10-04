<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barcode'       => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'sku'           => ['required', 'string', 'max:80', 'unique:products,sku'],
            'name'          => ['required', 'string', 'max:200'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'price_usd'     => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'min_stock'     => ['required', 'integer', 'min:0'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'category_id'   => ['required', 'exists:categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'is_active'     => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required'         => 'El SKU del producto es obligatorio.',
            'sku.unique'           => 'Ya existe un producto con ese SKU.',
            'barcode.unique'       => 'Ya existe un producto con ese código de barras.',
            'name.required'        => 'El nombre del producto es obligatorio.',
            'price_usd.required'   => 'El precio en USD es obligatorio.',
            'price_usd.min'        => 'El precio no puede ser negativo.',
            'min_stock.required'   => 'El stock mínimo es obligatorio.',
            'min_stock.min'        => 'El stock mínimo no puede ser negativo.',
            'current_stock.min'    => 'El stock actual no puede ser negativo.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists'   => 'La categoría seleccionada no existe.',
            'supplier_id.exists'   => 'El proveedor seleccionado no existe.',
        ];
    }
}
