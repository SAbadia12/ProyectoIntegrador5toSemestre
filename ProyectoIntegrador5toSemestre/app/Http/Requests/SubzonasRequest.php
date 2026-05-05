<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubzonasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subzona' => [
                'required',
                'string',
                'max:255',
            ],
            'id_zona' => [
                'required',
                'integer',
                'exists:zonas,id_zona',
            ],
            'tipo_subzona' => [
                'required',
                'integer',
                'exists:subzonas_tipos,id_subtipo',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subzona.required' => 'El nombre de la subzona es obligatorio.',
            'subzona.string' => 'El nombre de la subzona debe ser una cadena de texto.',
            'subzona.max' => 'El nombre de la subzona no puede superar 255 caracteres.',
            'id_zona.required' => 'La zona es obligatoria.',
            'id_zona.integer' => 'La zona debe ser un valor numérico válido.',
            'id_zona.exists' => 'La zona seleccionada no existe.',
            'tipo_subzona.required' => 'El subtipo de subzona es obligatorio.',
            'tipo_subzona.integer' => 'El subtipo de subzona debe ser un valor numérico válido.',
            'tipo_subzona.exists' => 'El subtipo de subzona seleccionado no existe.',
        ];
    }
}