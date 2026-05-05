<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubzonasRequest;
use App\Models\Subzonas;
use App\Models\Zonas;
use App\Models\SubzonasTipo;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


class SubzonasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subzonas::with(['zona', 'subzonasTipo']);

        if ($request->filled('buscar')) {
            $search = '%' . $request->get('buscar') . '%';
            $query->where(function ($subquery) use ($search) {
                $subquery->where('subzona', 'like', $search)
                    ->orWhereHas('zona', function ($q) use ($search) {
                        $q->where('zona', 'like', $search);
                    });
            });
        }

        $subzonas = $query->orderBy('id_subzona', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('subzonas.index', compact('subzonas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zonas = Zonas::all();
        $subzonas_tipos = SubzonasTipo::all();
        return view('subzonas.create', compact('zonas', 'subzonas_tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubzonasRequest $request)
    {
        $validated = $request->validated();

        Subzonas::create($validated);

        return redirect()->route('subzonas.index')
            ->with('success', 'Subzona creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subzonas $subzonas)
    {
        return view('subzonas.show', compact('subzonas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subzonas $subzonas)
    {
        $zonas = Zonas::all();
        $subzonas_tipos = SubzonasTipo::all();
        return view('subzonas.edit', compact('subzonas', 'zonas', 'subzonas_tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubzonasRequest $request, Subzonas $subzonas)
    {
        $validated = $request->validated();

        $subzonas->update($validated);

        return redirect()->route('subzonas.index')
            ->with('success', 'Subzona actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subzonas $subzonas)
    {
        try {
            $subzonas->delete();
            return redirect()->route('subzonas.index')
                ->with('success', 'Subzona eliminada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('subzonas.index')
                    ->with('error', 'No se puede eliminar. Esta subzona está asociada a otros registros.');
            }
            throw $e;
        }
    }
}
