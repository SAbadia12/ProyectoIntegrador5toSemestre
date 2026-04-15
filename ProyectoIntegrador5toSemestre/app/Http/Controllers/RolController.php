<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\RolRequest;
use Illuminate\Database\QueryException;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Rol::all();

        $query = Rol::query();
        // aplicar filtros si existen
        if ($request->filled('buscar')) {
            $query->where('rol', 'like', '%' . $request->buscar . '%');
        }
        $roles = $query->orderBy('id_rol', 'desc')->paginate(2)->appends($request->all());
        return view('rol.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('rol.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolRequest $request)
    {
        //
        Rol::create($request->validated());
        return redirect()->route('rol.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $roles)
    {
        //
        return view('rol.show', compact('roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        //
        return view('rol.edit', ["rol" => $rol]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolRequest $request, Rol $rol)
    {
        //
        $rol->update($request->validated());
        return redirect()->route('rol.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $roles)
    {
        //
        try {
            $roles->delete();
            return redirect()->route('rol.index')
                ->with('success', 'Rol eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('rol.index')
                    ->with('error', 'No se puede eliminar. Este Rol está asociado a otros registros.');
            }
            return redirect()->route('rol.index')
                ->with('error', 'Ocurrió un error inesperado al eliminar.');
        }
    }
}
