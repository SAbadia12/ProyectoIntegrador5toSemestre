<?php

namespace App\Http\Controllers;

use App\Models\SubzonasTipo;
use Illuminate\Database\QueryException;
use App\Http\Requests\SubzonasTipoRequest;
use Illuminate\Http\Request;

class SubzonasTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubzonasTipo::query();

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where('subtipo', 'like', $search);
        }

        $subzonas_tipos = $query->orderBy('id_subtipo', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('subzonas_tipo.index', compact('subzonas_tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subzonas_tipo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubzonasTipoRequest $request)
    {
        $validated = $request->validated();

        SubzonasTipo::create($validated);

        return redirect()->route('subzonas_tipo.index')
            ->with('success', 'Subtipo de zona creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubzonasTipo $subzonasTipo)
    {
        return view('subzonas_tipo.show', compact('subzonasTipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubzonasTipo $subzonasTipo)
    {
        return view('subzonas_tipo.edit', compact('subzonasTipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubzonasTipoRequest $request, SubzonasTipo $subzonasTipo)
    {
        $validated = $request->validated();

        $subzonasTipo->update($validated);

        return redirect()->route('subzonas_tipo.index')
            ->with('success', 'Subtipo de zona actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubzonasTipo $subzonasTipo)
    {
        try {
            $subzonasTipo->delete();
            return redirect()->route('subzonas_tipo.index')
                ->with('success', 'Subtipo de zona eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('subzonas_tipo.index')
                    ->with('error', 'No se puede eliminar. Este subtipo de zona está asociado a otros registros.');
            }
            throw $e;
        }
    }
}
