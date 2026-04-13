<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PuntoCardinalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si es edición, excluye el ID actual en unique
        $id = $this->route('punto_cardinal')?->id_punto_cardinal;

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                
            ],
            
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'El nombre no puede tener más de 255 caracteres.',
            
        ];
    }
}