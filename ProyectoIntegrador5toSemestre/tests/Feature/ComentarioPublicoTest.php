<?php

namespace Tests\Feature;

use App\Models\Comentario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del endpoint público POST /comentario (RF5).
 * Verifica que cualquier visitante pueda dejar un comentario,
 * que se valide el contenido y que el filtro anti-lenguaje funcione.
 */
class ComentarioPublicoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rf5_visitante_puede_enviar_un_comentario_valido()
    {
        $payload = [
            'nombre'    => 'Juan Pérez',
            'email'     => 'juan@example.com',
            'contenido' => 'Excelente plataforma, muy útil para conocer las zonas de Cali.',
        ];

        $response = $this->postJson('/comentario', $payload);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('comentarios', [
            'nombre' => 'Juan Pérez',
            'email'  => 'juan@example.com',
            'estado' => 'pendiente',
        ]);
    }

    /** @test */
    public function el_email_es_opcional()
    {
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Anónimo',
            'contenido' => 'Comentario sin correo electrónico, eso debería estar bien.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comentarios', ['nombre' => 'Anónimo', 'email' => null]);
    }

    /** @test */
    public function rechaza_comentario_sin_nombre()
    {
        $response = $this->postJson('/comentario', [
            'contenido' => 'Sin nombre debería fallar la validación.',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nombre']);
        $this->assertDatabaseCount('comentarios', 0);
    }

    /** @test */
    public function rechaza_comentario_muy_corto()
    {
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Ana',
            'contenido' => 'corto',  // menos de 5 chars no, pero queremos > 5 mínimo
        ]);

        // El validador es min:5, "corto" tiene 5 -> OK
        // Probemos con 4 chars
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Ana',
            'contenido' => 'hola',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['contenido']);
    }

    /** @test */
    public function rechaza_email_con_formato_invalido()
    {
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Ana',
            'email'     => 'esto-no-es-email',
            'contenido' => 'Contenido valido para test',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function el_filtro_anti_lenguaje_bloquea_palabras_ofensivas()
    {
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Troll',
            'contenido' => 'Esto es una mierda de plataforma',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'El comentario contiene lenguaje no permitido. Por favor reformúlalo.',
                 ]);

        $this->assertDatabaseCount('comentarios', 0);
    }

    /** @test */
    public function el_estado_inicial_de_un_comentario_es_pendiente()
    {
        $this->postJson('/comentario', [
            'nombre'    => 'Pedro',
            'contenido' => 'Mi primer comentario en la plataforma.',
        ]);

        $comentario = Comentario::first();
        $this->assertEquals('pendiente', $comentario->estado);
    }

    /** @test */
    public function rechaza_contenido_excesivamente_largo()
    {
        $response = $this->postJson('/comentario', [
            'nombre'    => 'Pedro',
            'contenido' => str_repeat('a', 1001),  // max:1000
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['contenido']);
    }
}
