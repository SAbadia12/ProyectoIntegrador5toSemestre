<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller para comentarios:
 *   - store():  endpoint público (RF5) - cualquier visitante puede dejar comentario
 *   - index():  moderador/admin - listar (RF11)
 *   - aprobar()/rechazar(): moderador (RF11)
 *   - destroy(): moderador (RF13)
 */
class ComentarioController extends Controller
{
    /**
     * Endpoint público: guardar comentario desde el visitante.
     * Devuelve JSON para integrarse con el fetch del frontend.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:100',
            'email'     => 'nullable|email|max:150',
            'contenido' => 'required|string|min:5|max:1000',
        ]);

        // Filtro básico anti-lenguaje ofensivo (lista corta inicial)
        $palabrasProhibidas = ['mierda', 'puta', 'gilipollas', 'pendejo', 'idiota', 'imbecil'];
        $textoLower = mb_strtolower($validated['contenido']);
        foreach ($palabrasProhibidas as $palabra) {
            if (str_contains($textoLower, $palabra)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El comentario contiene lenguaje no permitido. Por favor reformúlalo.',
                ], 422);
            }
        }

        $comentario = Comentario::create([
            'nombre'    => $validated['nombre'],
            'email'     => $validated['email'] ?? null,
            'contenido' => $validated['contenido'],
            'estado'    => Comentario::ESTADO_PENDIENTE,
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Gracias por tu comentario! Será revisado pronto.',
            'data'    => [
                'id'         => $comentario->id_comentario,
                'nombre'     => $comentario->nombre,
                'estado'     => $comentario->estado,
            ],
        ], 201);
    }

    /**
     * Bandeja de comentarios para el moderador (RF11).
     */
    public function index(Request $request)
    {
        $query = Comentario::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $comentarios = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all());

        return view('comentario.index', compact('comentarios'));
    }

    /**
     * Aprobar comentario.
     */
    public function aprobar(Comentario $comentario)
    {
        $comentario->update(['estado' => Comentario::ESTADO_APROBADO]);
        return back()->with('success', 'Comentario aprobado.');
    }

    /**
     * Rechazar comentario.
     */
    public function rechazar(Comentario $comentario)
    {
        $comentario->update(['estado' => Comentario::ESTADO_RECHAZADO]);
        return back()->with('success', 'Comentario rechazado.');
    }

    /**
     * Eliminar comentario (RF13).
     */
    public function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return back()->with('success', 'Comentario eliminado.');
    }
}
