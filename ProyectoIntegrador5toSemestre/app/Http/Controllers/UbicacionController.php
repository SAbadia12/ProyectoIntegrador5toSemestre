<?php

namespace App\Http\Controllers;

use App\Models\NivelRiesgo;
use App\Models\PuntoCardinal;
use App\Models\Ubicacion;
use App\Models\Subzonas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Lista las ubicaciones con filtros opcionales (búsqueda por dirección).
     */
    public function index(Request $request)
    {
        $query = Ubicacion::with(['nivel', 'puntoCardinal', 'subzona']);

        if ($request->filled('buscar')) {
            $query->where('direccion', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('nivel')) {
            $query->where('id_nivel', $request->nivel);
        }

        if ($request->filled('subzona')) {
            $query->where('id_subzona', $request->subzona);
        }

        $ubicaciones = $query->orderBy('id_ubicacion', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // Para el dropdown de filtros
        $niveles = NivelRiesgo::all();
        $subzonas   = Subzonas::all();

        return view('ubicacion.index', compact('ubicaciones', 'niveles', 'subzonas'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $niveles         = NivelRiesgo::all();
        $puntosCardinales = PuntoCardinal::all();
        $subzonas           = Subzonas::all();

        return view('ubicacion.create', compact('niveles', 'puntosCardinales', 'subzonas'));
    }

    /**
     * Guarda una nueva ubicación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'direccion'         => 'required|string|max:255',
            'latitud'           => 'nullable|numeric|between:-90,90',
            'longitud'          => 'nullable|numeric|between:-180,180',
            'id_nivel'          => 'required|integer|exists:nivel_riesgos,id_nivel_riesgo',
            'id_punto_cardinal' => 'required|integer|exists:puntos_cardinales,id_punto_cardinal',
            'id_subzona'           => 'required|integer|exists:subzonas,id_subzona',
        ]);

        Ubicacion::create($validated);

        return redirect()->route('ubicacion.index')
            ->with('success', 'Ubicación creada exitosamente');
    }

    /**
     * Muestra el detalle de una ubicación.
     */
    public function show(Ubicacion $ubicacion)
    {
        $ubicacion->load(['nivel', 'puntoCardinal', 'subzona']);
        return view('ubicacion.show', compact('ubicacion'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Ubicacion $ubicacion)
    {
        $niveles         = NivelRiesgo::all();
        $puntosCardinales = PuntoCardinal::all();
        $subzonas           = Subzonas::all();

        return view('ubicacion.edit', compact('ubicacion', 'niveles', 'puntosCardinales', 'subzonas'));
    }

    /**
     * Actualiza una ubicación existente.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $validated = $request->validate([
            'direccion'         => 'required|string|max:255',
            'latitud'           => 'nullable|numeric|between:-90,90',
            'longitud'          => 'nullable|numeric|between:-180,180',
            'id_nivel'          => 'required|integer|exists:nivel_riesgos,id_nivel_riesgo',
            'id_punto_cardinal' => 'required|integer|exists:puntos_cardinales,id_punto_cardinal',
            'id_subzona'           => 'required|integer|exists:subzonas,id_subzona',
        ]);

        $ubicacion->update($validated);

        return redirect()->route('ubicacion.index')
            ->with('success', 'Ubicación actualizada exitosamente');
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
