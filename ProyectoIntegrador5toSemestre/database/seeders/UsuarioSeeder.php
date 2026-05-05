<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

/**
 * Crea usuarios de prueba para cada rol del sistema.
 *
 * Roles (tabla 'roles'):
 *   1 = Administrador
 *   2 = Moderador de Contenido
 *   3 = Analista de Seguridad
 *
 * Las contraseñas se hashean automáticamente por el mutator
 * setPasswordAttribute() del modelo Usuario.
 *
 * Idempotente: si el email ya existe, actualiza al usuario en lugar de duplicar.
 *
 * CREDENCIALES DE PRUEBA:
 *   Administrador            admin@sisc.com       / admin123
 *   Moderador de Contenido   moderador@sisc.com   / moderador123
 *   Analista de Seguridad    analista@sisc.com    / analista123
 *   Usuario inactivo         inactivo@sisc.com    / inactivo123  (para probar RF16)
 */
class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'email'    => 'admin@sisc.com',
                'nombre'   => 'Carlos',
                'apellido' => 'Administrador',
                'password' => 'admin123',
                'rol'      => 1,
                'activo'   => true,
            ],
            [
                'email'    => 'moderador@sisc.com',
                'nombre'   => 'María',
                'apellido' => 'Moderadora',
                'password' => 'moderador123',
                'rol'      => 2,
                'activo'   => true,
            ],
            [
                'email'    => 'analista@sisc.com',
                'nombre'   => 'Andrés',
                'apellido' => 'Analista',
                'password' => 'analista123',
                'rol'      => 3,
                'activo'   => true,
            ],
            [
                'email'    => 'inactivo@sisc.com',
                'nombre'   => 'Usuario',
                'apellido' => 'Inactivo',
                'password' => 'inactivo123',
                'rol'      => 3,
                'activo'   => false,   // para probar bloqueo de login
            ],
        ];

        foreach ($usuarios as $u) {
            $existente = Usuario::where('email', $u['email'])->first();

            if ($existente) {
                $existente->nombre   = $u['nombre'];
                $existente->apellido = $u['apellido'];
                $existente->password = $u['password'];   // mutator hashea
                $existente->rol      = $u['rol'];
                $existente->activo   = $u['activo'];
                $existente->save();
            } else {
                Usuario::create([
                    'nombre'   => $u['nombre'],
                    'apellido' => $u['apellido'],
                    'email'    => $u['email'],
                    'password' => $u['password'],        // mutator hashea
                    'rol'      => $u['rol'],
                    'activo'   => $u['activo'],
                    'imagen'   => null,
                ]);
            }
        }
    }
}
