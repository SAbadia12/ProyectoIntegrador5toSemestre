<?php

namespace App\Http\Controllers;

use App\Models\Zonas;
use App\Models\ZonasTipo;
use Illuminate\Database\QueryException;
use App\Http\Requests\ZonasRequest;
use Illuminate\Http\Request;

class ZonasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Zonas::with('zonasTipo');

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where(function ($subquery) use ($search) {
                $subquery->where('zona', 'like', $search)
                    ->orWhereHas('zonasTipo', function ($q) use ($search) {
                        $q->where('tipo', 'like', $search);
                    });
            });
        }

        $zonas = $query->orderBy('id_zona', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('zonas.index', compact('zonas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zonas_tipos = ZonasTipo::all();
        return view('zonas.create', compact('zonas_tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZonasRequest $request)
    {
        $validated = $request->validated();

        Zonas::create($validated);

        return redirect()->route('zonas.index')
            ->with('success', 'Zona creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zonas $zonas)
    {
        return view('zonas.show', compact('zonas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zonas $zonas)
    {
        $zonas_tipos = ZonasTipo::all();
        return view('zonas.edit', ['zonas' => $zonas, 'zonas_tipos' => $zonas_tipos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZonasRequest $request, Zonas $zonas)
    {
        $validated = $request->validated();

        $zonas->update($validated);

        return redirect()->route('zonas.index')
            ->with('success', 'Zona actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zonas $zonas)
    {
        try {
            $zonas->delete();
            return redirect()->route('zonas.index')
                ->with('success', 'Zona eliminada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('zonas.index')
                    ->with('error', 'No se puede eliminar. Esta zona está asociada a otros registros.');
            }
            throw $e;
        }
    }
}
