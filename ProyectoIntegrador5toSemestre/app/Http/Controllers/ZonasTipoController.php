<?php

namespace App\Http\Controllers;

use App\Models\ZonasTipo;
use Illuminate\Http\Request;
use App\Http\Requests\ZonasTipoRequest;
use Illuminate\Database\QueryException;

class ZonasTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ZonasTipo::query();

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where('tipo', 'like', $search);
        }

        $zonas_tipos = $query->orderBy('id_tipo', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('zonas_tipo.index', compact('zonas_tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('zonas_tipo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZonasTipoRequest $request)
    {
        $validated = $request->validated();

        ZonasTipo::create($validated);

        return redirect()->route('zonas_tipo.index')
            ->with('success', 'Tipo de zona creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ZonasTipo $zonas_tipo)
    {
        return view('zonas_tipo.show', compact('zonas_tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ZonasTipo $zonas_tipo)
    {
        return view('zonas_tipo.edit', compact('zonas_tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZonasTipoRequest $request, ZonasTipo $zonas_tipo)
    {
        $validated = $request->validated();

        $zonas_tipo->update($validated);

        return redirect()->route('zonas_tipo.index')
            ->with('success', 'Tipo de zona actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZonasTipo $zonas_tipo)
    {
        try {
            $zonas_tipo->delete();
            return redirect()->route('zonas_tipo.index')
                ->with('success', 'Tipo de zona eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('zonas_tipo.index')
                    ->with('error', 'No se puede eliminar. Este tipo de zona está asociado a otros registros.');
            }
            throw $e;
        }
    }
}
