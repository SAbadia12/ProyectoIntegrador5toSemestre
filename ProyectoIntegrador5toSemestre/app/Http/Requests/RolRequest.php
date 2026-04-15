<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si es edición, excluye el ID actual en unique
        $id = $this->route('rol')?->id_rol;

        return [
            'rol' => [
                'required',
                'regex:/^[\pL\s]+$/u',
                'max:255',
                
            ],
            
        ];
    }

    public function messages(): array
    {
        return [
            'rol.required' => 'El rol es obligatorio.',
            'rol.regex'   => 'El rol solo puede contener letras y espacios.',
            'rol.max'      => 'El rol no puede tener más de 255 caracteres.',
            
        ];
    }
}