<?php

namespace App\Http\Controllers;

use App\Models\Delito;
use App\Models\Ubicacion;
use Illuminate\Database\QueryException;
use App\Http\Requests\DelitoRequest;
use Illuminate\Http\Request;

class DelitoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Delito::with('ubicaciones');

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where('tipo', 'like', $search)
                ->orWhere('descripcion', 'like', $search);
        }

        $delitos = $query->orderBy('id_delito', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('delitos.index', compact('delitos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        return view('delitos.create', compact('ubicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DelitoRequest $request)
    {
        $validated = $request->validated();
        $ubicaciones = $validated['ubicaciones'] ?? [];
        unset($validated['ubicaciones']);

        $delito = Delito::create($validated);

        // Asociar ubicaciones con fechas
        if (!empty($ubicaciones)) {
            $delitoUbicaciones = [];
            foreach ($ubicaciones as $ubicacion) {
                $delitoUbicaciones[$ubicacion['id_ubicacion']] = ['fecha' => $ubicacion['fecha']];
            }
            $delito->ubicaciones()->attach($delitoUbicaciones);
        }

        return redirect()->route('delitos.index')
            ->with('success', 'Delito creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delito $delito)
    {
        return view('delitos.show', compact('delito'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delito $delito)
    {
        $ubicaciones = Ubicacion::all();
        $delitoUbicaciones = $delito->ubicaciones()->get();
        return view('delitos.edit', compact('delito', 'ubicaciones', 'delitoUbicaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DelitoRequest $request, Delito $delito)
    {
        $validated = $request->validated();
        $ubicaciones = $validated['ubicaciones'] ?? [];
        unset($validated['ubicaciones']);

        $delito->update($validated);

        // Actualizar ubicaciones
        if (!empty($ubicaciones)) {
            $delitoUbicaciones = [];
            foreach ($ubicaciones as $ubicacion) {
                $delitoUbicaciones[$ubicacion['id_ubicacion']] = ['fecha' => $ubicacion['fecha']];
            }
            $delito->ubicaciones()->sync($delitoUbicaciones);
        } else {
            $delito->ubicaciones()->detach();
        }

        return redirect()->route('delitos.index')
            ->with('success', 'Delito actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delito $delito)
    {
        try {
            $delito->delete();
            return redirect()->route('delitos.index')
                ->with('success', 'Delito eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('delitos.index')
                    ->with('error', 'No se puede eliminar. Este delito está asociado a otros registros.');
            }
            throw $e;
        }
    }
}
