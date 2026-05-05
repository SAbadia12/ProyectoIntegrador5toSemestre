<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZonasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zona' => [
                'required',
                'string',
                'max:255',
            ],
            'tipo_zona' => [
                'required',
                'integer',
                'exists:zonas_tipos,id_tipo',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'zona.required' => 'El nombre de la zona es obligatorio.',
            'zona.string' => 'El nombre de la zona debe ser una cadena de texto.',
            'zona.max' => 'El nombre de la zona no puede tener más de 255 caracteres.',
            'tipo_zona.required' => 'El tipo de zona es obligatorio.',
            'tipo_zona.integer' => 'El tipo de zona debe ser un número entero.',
            'tipo_zona.exists' => 'El tipo de zona seleccionado no existe.',
        ];
    }
}