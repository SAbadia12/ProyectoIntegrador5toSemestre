<?php

namespace App\Http\Controllers;
use App\Http\Requests\PuntoCardinalRequest;
use App\Models\PuntoCardinal;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PuntoCardinalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //$puntoCardinal = PuntoCardinal::orderBy('id_punto_cardinal', 'desc')->paginate(6);
        //return view('cardinal.index', compact('puntoCardinal'));

        //$nivelRiesgo = NivelRiesgo::orderBy('id_nivel_riesgo', 'desc')->paginate(6);
        //return view('nivel.index', compact('nivelRiesgo'));
        $puntoCardinal = PuntoCardinal::all();

        $query = PuntoCardinal::query();
        // aplicar filtros si existen
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        $puntoCardinal = $query->orderBy('id_punto_cardinal', 'desc')->paginate(6);
        return view('cardinal.index', compact('puntoCardinal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('cardinal.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PuntoCardinalRequest $request)
    {
        //
        PuntoCardinal::create($request->validated());
        return redirect()->route('cardinal.index')
            ->with('success', 'Punto cardinal creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PuntoCardinal $puntoCardinal)
    {
        //
        return view('cardinal.show', compact('puntoCardinal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PuntoCardinal $cardinal)
    {
        //
        return view('cardinal.edit', ["cardinal" => $cardinal]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PuntoCardinalRequest $request, PuntoCardinal $cardinal)
    {
        //
        $cardinal->update($request->validated());
        return redirect()->route('cardinal.index')
            ->with('success', 'Punto Cardinal actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PuntoCardinal $cardinal)
    {
        //
        try {
            $cardinal->delete();
            return redirect()->route('cardinal.index')
                ->with('success', 'Punto Cardinal eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('cardinal.index')
                    ->with('error', 'No se puede eliminar. Este Punto Cardinal está asociado a otros registros.');
            }
            return redirect()->route('cardinal.index')
                ->with('error', 'Ocurrió un error inesperado al eliminar.');
        }
    }
}
