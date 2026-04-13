<?php

namespace App\Http\Controllers;

use App\Models\NivelRiesgo;
use Illuminate\Http\Request;
use App\Http\Requests\NivelRiesgoRequest;
use Illuminate\Database\QueryException;

class NivelRiesgoController extends Controller
{
    /**
     * Lista todos los niveles de riesgo
     */
    public function index()
    {
        $nivelRiesgo = NivelRiesgo::orderBy('id_nivel_riesgo', 'desc')->paginate(6);
        return view('nivel.index', compact('nivelRiesgo'));
    }

    /**
     * Muestra el formulario de creación
     */
    public function create()
    {
        return view('nivel.create');
    }

    /**
     * Guarda un nuevo nivel de riesgo
     */
    public function store(NivelRiesgoRequest $request)
    {
        NivelRiesgo::create($request->validated());
        return redirect()->route('nivel.index')
            ->with('success', 'Nivel de riesgo creado exitosamente.');
    }

    /**
     * Muestra el detalle de un nivel de riesgo
     */
    public function show(NivelRiesgo $NivelRiesgo)
    {
        return view('nivel.show', compact('NivelRiesgo'));
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit(NivelRiesgo $nivel)
    {
        return view('nivel.edit', ["nivel" => $nivel]);
    }

    /**
     * Actualiza un nivel de riesgo existente
     */
    public function update(NivelRiesgoRequest $request, NivelRiesgo $nivel)
    {
        $nivel->update($request->validated());
        return redirect()->route('nivel.index')
            ->with('success', 'Nivel de riesgo actualizado exitosamente.');
    }

    /**
     * Elimina un nivel de riesgo
     */
    public function destroy(NivelRiesgo $nivel)
    {
        try {
            $nivel->delete();
            return redirect()->route('nivel.index')
                ->with('success', 'Nivel de riesgo eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('nivel.index')
                    ->with('error', 'No se puede eliminar. Este nivel está asociado a otros registros.');
            }
            return redirect()->route('nivel.index')
                ->with('error', 'Ocurrió un error inesperado al eliminar.');
        }
    }
}