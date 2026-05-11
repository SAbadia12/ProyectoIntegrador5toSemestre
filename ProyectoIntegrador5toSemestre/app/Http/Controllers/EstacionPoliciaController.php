<?php

namespace App\Http\Controllers;

use App\Models\EstacionPolicia;
use Illuminate\Database\QueryException;
use App\Http\Requests\EstacionPoliciaRequest;
use App\Models\Subzonas;
use Illuminate\Http\Request;

class EstacionPoliciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EstacionPolicia::with('subzona');

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where(function ($subquery) use ($search) {
                $subquery->where('nombre', 'like', $search)
                    ->orWhere('direccion', 'like', $search)
                    ->orWhere('telefono', 'like', $search);
            });
        }

        $estaciones = $query->orderBy('id_estacion', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('estaciones.index', compact('estaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subzonas = Subzonas::all();
        return view('estaciones.create', compact('subzonas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstacionPoliciaRequest $request)
    {
        $validated = $request->validated();

        EstacionPolicia::create($validated);

        return redirect()->route('estaciones.index')
            ->with('success', 'Estación de policía creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EstacionPolicia $estacion)
    {
        return view('estaciones.show', compact('estacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstacionPolicia $estacion)
    {
        $subzonas = Subzonas::all();
        return view('estaciones.edit', ['estacion' => $estacion, 'subzonas' => $subzonas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstacionPoliciaRequest $request, EstacionPolicia $estacion)
    {
        $validated = $request->validated();

        $estacion->update($validated);

        return redirect()->route('estaciones.index')
            ->with('success', 'Estación de policía actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstacionPolicia $estacion)
    {
        try {
            $estacion->delete();
            return redirect()->route('estaciones.index')
                ->with('success', 'Estación de policía eliminada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('estaciones.index')
                    ->with('error', 'No se puede eliminar. Esta estación está asociada a otros registros.');
            }
            throw $e;
        }
    }
}
