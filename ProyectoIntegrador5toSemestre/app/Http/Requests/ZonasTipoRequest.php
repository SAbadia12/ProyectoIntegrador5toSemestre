<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZonasTipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('zonas_tipo')?->id_tipo;

        return [
            'tipo' => [
                'required',
                'string',
                'max:255',
                'unique:zonas_tipos,tipo,' . $id . ',id_tipo',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo es obligatorio.',
            'tipo.string' => 'El tipo debe ser una cadena de texto.',
            'tipo.max' => 'El tipo no puede tener más de 255 caracteres.',
            'tipo.unique' => 'Este tipo ya existe.',
        ];
    }
}