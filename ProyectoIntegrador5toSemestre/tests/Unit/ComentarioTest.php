<?php

namespace Tests\Unit;

use App\Models\Comentario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas unitarias del modelo Comentario.
 * Verifican constantes, scopes y comportamiento aislado del modelo.
 */
class ComentarioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tiene_constantes_de_estado_correctas()
    {
        $this->assertEquals('pendiente', Comentario::ESTADO_PENDIENTE);
        $this->assertEquals('aprobado',  Comentario::ESTADO_APROBADO);
        $this->assertEquals('rechazado', Comentario::ESTADO_RECHAZADO);
    }

    /** @test */
    public function scope_pendientes_solo_devuelve_comentarios_en_estado_pendiente()
    {
        Comentario::create(['nombre' => 'Ana',  'contenido' => 'Comentario A', 'estado' => 'pendiente']);
        Comentario::create(['nombre' => 'Beto', 'contenido' => 'Comentario B', 'estado' => 'aprobado']);
        Comentario::create(['nombre' => 'Cris', 'contenido' => 'Comentario C', 'estado' => 'rechazado']);
        Comentario::create(['nombre' => 'Dany', 'contenido' => 'Comentario D', 'estado' => 'pendiente']);

        $pendientes = Comentario::pendientes()->get();

        $this->assertCount(2, $pendientes);
        $this->assertTrue($pendientes->every(fn ($c) => $c->estado === 'pendiente'));
    }

    /** @test */
    public function scope_aprobados_solo_devuelve_comentarios_aprobados()
    {
        Comentario::create(['nombre' => 'Ana',  'contenido' => 'Test', 'estado' => 'aprobado']);
        Comentario::create(['nombre' => 'Beto', 'contenido' => 'Test', 'estado' => 'pendiente']);
        Comentario::create(['nombre' => 'Cris', 'contenido' => 'Test', 'estado' => 'aprobado']);

        $aprobados = Comentario::aprobados()->get();

        $this->assertCount(2, $aprobados);
        $this->assertTrue($aprobados->every(fn ($c) => $c->estado === 'aprobado'));
    }

    /** @test */
    public function al_crear_un_comentario_el_estado_default_es_pendiente()
    {
        $c = Comentario::create([
            'nombre'    => 'Visitante',
            'contenido' => 'Excelente plataforma',
        ]);

        $this->assertEquals('pendiente', $c->estado);
    }

    /** @test */
    public function el_email_es_opcional()
    {
        $c = Comentario::create([
            'nombre'    => 'Anónimo',
            'contenido' => 'Sin email',
        ]);

        $this->assertNull($c->email);
        $this->assertDatabaseHas('comentarios', ['nombre' => 'Anónimo']);
    }
}
