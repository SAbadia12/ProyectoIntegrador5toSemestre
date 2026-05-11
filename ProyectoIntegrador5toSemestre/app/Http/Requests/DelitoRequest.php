<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DelitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => [
                'required',
                'string',
                'max:255',
            ],
            'gravedad' => [
                'required',
                'integer',
                'min:1',
                'max:3',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'ubicaciones' => [
                'nullable',
                'array',
            ],
            'ubicaciones.*.id_ubicacion' => [
                'required_with:ubicaciones',
                'integer',
                'exists:ubicaciones,id_ubicacion',
            ],
            'ubicaciones.*.fecha' => [
                'required_with:ubicaciones',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de delito es obligatorio.',
            'tipo.string' => 'El tipo debe ser una cadena de texto.',
            'tipo.max' => 'El tipo no puede tener más de 255 caracteres.',
            'gravedad.required' => 'La gravedad es obligatoria.',
            'gravedad.integer' => 'La gravedad debe ser un número entero.',
            'gravedad.min' => 'La gravedad debe ser mayor o igual a 1.',
            'gravedad.max' => 'La gravedad debe ser menor o igual a 3.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'ubicaciones.*.id_ubicacion.required_with' => 'La ubicación es requerida.',
            'ubicaciones.*.id_ubicacion.exists' => 'La ubicación seleccionada no existe.',
            'ubicaciones.*.fecha.required_with' => 'La fecha es requerida para cada ubicación.',
            'ubicaciones.*.fecha.date' => 'La fecha debe ser una fecha válida.',
        ];
    }
}
