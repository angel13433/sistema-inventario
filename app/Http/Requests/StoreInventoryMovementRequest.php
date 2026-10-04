<?php

namespace App\Http\Requests;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', new Enum(MovementType::class)],
            'quantity'   => ['required', 'integer', 'min:1'],
            'reason'     => ['required', 'string', 'max:255'],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Debe seleccionar un producto.',
            'product_id.exists'   => 'El producto seleccionado no existe.',
            'type.required'       => 'El tipo de movimiento es obligatorio.',
            'quantity.required'   => 'La cantidad es obligatoria.',
            'quantity.min'        => 'La cantidad debe ser al menos 1 unidad.',
            'reason.required'     => 'El motivo del movimiento es obligatorio.',
            'reason.max'          => 'El motivo no puede superar los 255 caracteres.',
        ];
    }
}
