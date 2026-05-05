<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas feature de autenticación y autorización.
 * Cubre RF1 (login), RF2 (validar credenciales), RF8 (logout), RF16 (bloquear inactivos).
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: crea un rol y un usuario activo para las pruebas.
     */
    protected function crearUsuario(array $overrides = []): Usuario
    {
        Rol::firstOrCreate(['id_rol' => 1], ['rol' => 'Administrador']);

        return Usuario::create(array_merge([
            'nombre'   => 'Test',
            'apellido' => 'User',
            'email'    => 'test@sisc.com',
            'password' => 'secret123',  // el mutator hashea
            'rol'      => 1,
            'activo'   => true,
        ], $overrides));
    }

    /** @test */
    public function login_con_credenciales_validas_redirige_a_plantilla()
    {
        $this->crearUsuario();

        $response = $this->post('/login', [
            'email'    => 'test@sisc.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('plantilla'));
        $this->assertNotNull(session('logged_user'));
    }

    /** @test */
    public function login_con_credenciales_invalidas_devuelve_error()
    {
        $this->crearUsuario();

        $response = $this->post('/login', [
            'email'    => 'test@sisc.com',
            'password' => 'contraseña-incorrecta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Credenciales incorrectas.');
        $this->assertNull(session('logged_user'));
    }

    /** @test */
    public function login_con_email_inexistente_devuelve_error()
    {
        $response = $this->post('/login', [
            'email'    => 'fantasma@sisc.com',
            'password' => 'cualquiera',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Credenciales incorrectas.');
    }

    /** @test */
    public function rf16_usuario_inactivo_no_puede_loguearse()
    {
        $this->crearUsuario(['activo' => false]);

        $response = $this->post('/login', [
            'email'    => 'test@sisc.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tu cuenta está desactivada. Contacta al administrador.');
        $this->assertNull(session('logged_user'));
    }

    /** @test */
    public function logout_limpia_la_sesion()
    {
        $usuario = $this->crearUsuario();

        // Simular usuario logueado
        $this->withSession(['logged_user' => $usuario->id_usuario]);

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertNull(session('logged_user'));
    }

    /** @test */
    public function el_password_se_hashea_automaticamente_al_crear_usuario()
    {
        $usuario = $this->crearUsuario();

        // El password no debe estar guardado en texto plano
        $this->assertNotEquals('secret123', $usuario->password);
        // Pero debe poder verificarse
        $this->assertTrue(\Hash::check('secret123', $usuario->password));
    }
}
