<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubzonasTipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subzonas_tipo')?->id_subtipo;

        return [
            'subtipo' => [
                'required',
                'string',
                'max:255',
                'unique:subzonas_tipos,subtipo,' . $id . ',id_subtipo',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subtipo.required' => 'El subtipo es obligatorio.',
            'subtipo.string' => 'El subtipo debe ser una cadena de texto.',
            'subtipo.max' => 'El subtipo no puede tener más de 255 caracteres.',
            'subtipo.unique' => 'Este subtipo ya existe.',
        ];
    }
}