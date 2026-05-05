<?php

namespace App\Http\Controllers;

use App\Models\NivelRiesgo;
use App\Models\PuntoCardinal;
use App\Models\Ubicacion;
use App\Models\Zonas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Lista las ubicaciones con filtros opcionales (búsqueda por dirección).
     */
    public function index(Request $request)
    {
        $query = Ubicacion::with(['nivel', 'puntoCardinal', 'zona']);

        if ($request->filled('buscar')) {
            $query->where('direccion', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('nivel')) {
            $query->where('id_nivel', $request->nivel);
        }

        if ($request->filled('zona')) {
            $query->where('id_zona', $request->zona);
        }

        $ubicaciones = $query->orderBy('id_ubicacion', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // Para el dropdown de filtros
        $niveles = NivelRiesgo::all();
        $zonas   = Zonas::all();

        return view('ubicacion.index', compact('ubicaciones', 'niveles', 'zonas'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $niveles         = NivelRiesgo::all();
        $puntosCardinales = PuntoCardinal::all();
        $zonas           = Zonas::all();

        return view('ubicacion.create', compact('niveles', 'puntosCardinales', 'zonas'));
    }

    /**
     * Guarda una nueva ubicación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'direccion'         => 'required|string|max:255',
            'id_nivel'          => 'required|integer|exists:nivel_riesgos,id_nivel_riesgo',
            'id_punto_cardinal' => 'required|integer|exists:puntos_cardinales,id_punto_cardinal',
            'id_zona'           => 'required|integer|exists:zonas,id_zona',
        ]);

        Ubicacion::create($validated);

        return redirect()->route('ubicacion.index')
            ->with('success', 'Ubicación creada exitosamente.');
    }

    /**
     * Muestra el detalle de una ubicación.
     */
    public function show(Ubicacion $ubicacion)
    {
        $ubicacion->load(['nivel', 'puntoCardinal', 'zona']);
        return view('ubicacion.show', compact('ubicacion'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Ubicacion $ubicacion)
    {
        $niveles         = NivelRiesgo::all();
        $puntosCardinales = PuntoCardinal::all();
        $zonas           = Zonas::all();

        return view('ubicacion.edit', compact('ubicacion', 'niveles', 'puntosCardinales', 'zonas'));
    }

    /**
     * Actualiza una ubicación existente.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $validated = $request->validate([
            'direccion'         => 'required|string|max:255',
            'id_nivel'          => 'required|integer|exists:nivel_riesgos,id_nivel_riesgo',
            'id_punto_cardinal' => 'required|integer|exists:puntos_cardinales,id_punto_cardinal',
            'id_zona'           => 'required|integer|exists:zonas,id_zona',
        ]);

        $ubicacion->update($validated);

        return redirect()->route('ubicacion.index')
            ->with('success', 'Ubicación actualizada exitosamente.');
    }

    /**
     * Elimina una ubicación.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        try {
            $ubicacion->delete();
            return redirect()->route('ubicacion.index')
                ->with('success', 'Ubicación eliminada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('ubicacion.index')
                    ->with('error', 'No se puede eliminar. Esta ubicación está asociada a otros registros.');
            }
            return redirect()->route('ubicacion.index')
                ->with('error', 'Ocurrió un error inesperado al eliminar.');
        }
    }
}
