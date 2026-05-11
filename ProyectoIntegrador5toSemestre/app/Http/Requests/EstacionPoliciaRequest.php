<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstacionPoliciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => [
                'required',
                'string',
                'max:500',
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],
            'latitud' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],
            'longitud' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
            'id_subzona' => [
                'required',
                'integer',
                'exists:subzonas,id_subzona',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la estación es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede tener más de 500 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'latitud.numeric' => 'La latitud debe ser un número.',
            'latitud.between' => 'La latitud debe estar entre -90 y 90.',
            'longitud.numeric' => 'La longitud debe ser un número.',
            'longitud.between' => 'La longitud debe estar entre -180 y 180.',
            'id_subzona.required' => 'La subzona es obligatoria.',
            'id_subzona.exists' => 'La subzona seleccionada no existe.',
        ];
    }
}
