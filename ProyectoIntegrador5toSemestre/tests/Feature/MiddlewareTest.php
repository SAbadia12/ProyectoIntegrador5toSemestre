<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de los middlewares de seguridad.
 *  - auth.session: bloquea rutas internas si no hay sesión.
 *  - role: bloquea rutas si el rol logueado no coincide.
 */
class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles que necesita la app
        Rol::firstOrCreate(['id_rol' => 1], ['rol' => 'Administrador']);
        Rol::firstOrCreate(['id_rol' => 2], ['rol' => 'Moderador']);
        Rol::firstOrCreate(['id_rol' => 3], ['rol' => 'Analista']);
    }

    protected function crearUsuarioRol(int $rolId): Usuario
    {
        return Usuario::create([
            'nombre'   => 'User',
            'apellido' => 'Test',
            'email'    => "user{$rolId}@sisc.com",
            'password' => 'secret123',
            'rol'      => $rolId,
            'activo'   => true,
        ]);
    }

    /** @test */
    public function sin_login_no_se_puede_acceder_al_dashboard()
    {
        $response = $this->get('/plantilla');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Debes iniciar sesión para acceder a esta página.');
    }

    /** @test */
    public function sin_login_no_se_puede_acceder_a_usuarios()
    {
        $response = $this->get('/usuario');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_puede_acceder_al_listado_de_usuarios()
    {
        $admin = $this->crearUsuarioRol(1);

        $response = $this->withSession([
            'logged_user'     => $admin->id_usuario,
            'logged_user_rol' => 1,
        ])->get('/usuario');

        // 200 OK significa que pasó tanto auth.session como role:admin
        $response->assertStatus(200);
    }

    /** @test */
    public function moderador_no_puede_acceder_al_listado_de_usuarios()
    {
        $moderador = $this->crearUsuarioRol(2);

        $response = $this->withSession([
            'logged_user'     => $moderador->id_usuario,
            'logged_user_rol' => 2,
        ])->get('/usuario');

        // 403 = el middleware role bloquea
        $response->assertStatus(403);
    }

    /** @test */
    public function analista_no_puede_acceder_a_niveles()
    {
        $analista = $this->crearUsuarioRol(3);

        $response = $this->withSession([
            'logged_user'     => $analista->id_usuario,
            'logged_user_rol' => 3,
        ])->get('/nivel');

        $response->assertStatus(403);
    }

    /** @test */
    public function moderador_puede_acceder_a_niveles()
    {
        $moderador = $this->crearUsuarioRol(2);

        $response = $this->withSession([
            'logged_user'     => $moderador->id_usuario,
            'logged_user_rol' => 2,
        ])->get('/nivel');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_no_puede_desactivarse_a_si_mismo()
    {
        $admin = $this->crearUsuarioRol(1);

        $response = $this->withSession([
            'logged_user'     => $admin->id_usuario,
            'logged_user_rol' => 1,
        ])->patch("/usuario/{$admin->id_usuario}/toggle-activo");

        $response->assertRedirect(route('usuario.index'));
        $response->assertSessionHas('error', 'No puedes desactivar tu propia cuenta.');

        // Verificamos que sigue activo
        $admin->refresh();
        $this->assertTrue((bool) $admin->activo);
    }

    /** @test */
    public function admin_puede_desactivar_a_otro_usuario()
    {
        $admin = $this->crearUsuarioRol(1);
        $otro  = $this->crearUsuarioRol(2);

        $response = $this->withSession([
            'logged_user'     => $admin->id_usuario,
            'logged_user_rol' => 1,
        ])->patch("/usuario/{$otro->id_usuario}/toggle-activo");

        $response->assertRedirect(route('usuario.index'));

        $otro->refresh();
        $this->assertFalse((bool) $otro->activo);
    }
}
