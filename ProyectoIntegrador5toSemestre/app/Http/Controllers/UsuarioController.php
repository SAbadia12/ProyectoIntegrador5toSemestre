<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Usuario::with('rolRelacion');

        if ($request->filled('buscar')) {
            $search = '%' . $request->buscar . '%';
            $query->where(function ($subquery) use ($search) {
                $subquery->where('nombre', 'like', $search)
                    ->orWhere('apellido', 'like', $search)
                    ->orWhere('email', 'like', $search);
            });
        }

        $usuarios = $query->orderBy('id_usuario', 'desc')
            ->paginate(2)
            ->appends($request->all());

        return view('usuario.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::all();
        return view('usuario.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|integer|exists:roles,id_rol',
        ]);

        $imagen = $request->file('imagen');
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $imagen->getClientOriginalName());
        $destination = public_path('img/perfiles');
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        $imagen->move($destination, $filename);
        $validated['imagen'] = 'img/perfiles/' . $filename;

        Usuario::create($validated);

        return redirect()->route('usuario.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return view('usuario.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('usuario.edit', ['usuario' => $usuario, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario'),
            ],
            'password' => 'nullable|string|min:6',
            'rol' => 'required|integer|exists:roles,id_rol',
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $imagen->getClientOriginalName());
            $destination = public_path('img/perfiles');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $imagen->move($destination, $filename);
            $validated['imagen'] = 'img/perfiles/' . $filename;

            if ($usuario->imagen && file_exists(public_path($usuario->imagen))) {
                @unlink(public_path($usuario->imagen));
            }
        } else {
            unset($validated['imagen']);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('usuario.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        try {
            $usuario->delete();
            return redirect()->route('usuario.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('usuario.index')
                    ->with('error', 'No se puede eliminar. Este usuario está asociado a otros registros.');
            }
            return redirect()->route('usuario.index')
                ->with('error', 'Ocurrió un error inesperado al eliminar.');
        }
    }
}
