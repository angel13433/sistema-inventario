<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:200'],
            'rif'            => ['required', 'string', 'max:20', 'regex:/^[VEJPG]-\d{8}-\d$/', Rule::unique('suppliers', 'rif')->ignore($this->supplier)],
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:150'],
            'address'        => ['nullable', 'string', 'max:500'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'is_active'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El nombre del proveedor es obligatorio.',
            'rif.required'   => 'El RIF del proveedor es obligatorio.',
            'rif.unique'     => 'Ya existe un proveedor con ese RIF.',
            'rif.regex'      => 'El RIF debe tener el formato venezolano válido (ej: J-12345678-9).',
            'email.email'    => 'El correo electrónico no tiene un formato válido.',
        ];
    }
}
