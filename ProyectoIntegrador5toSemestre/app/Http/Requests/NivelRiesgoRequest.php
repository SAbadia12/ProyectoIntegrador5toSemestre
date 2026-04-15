<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NivelRiesgoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si es edición, excluye el ID actual en unique
        $id = $this->route('nivel_riesgo')?->id_nivel_riesgo;

        return [
            'nivel' => [
                'required',
                'regex:/^[\pL\s]+$/u', // Solo letras y espacios
                'max:255',
                'unique:nivel_riesgos,nivel,' . $id . ',id_nivel_riesgo',
            ],
            'color' => [
                'required',
                'max:255',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nivel.required' => 'El nivel de riesgo es obligatorio.',
            'nivel.regex'   => 'El nivel solo puede contener letras y espacios.',
            'nivel.max'      => 'El nivel no puede tener más de 255 caracteres.',
            'nivel.unique'   => 'Ya existe un nivel de riesgo con ese nombre.',

            'color.required' => 'El color es obligatorio.',
            'color.max'      => 'El color no puede tener más de 255 caracteres.',
            'color.regex'    => 'El color debe ser un código hexadecimal válido (ej: #FF0000).',
        ];
    }
}