@extends('layouts.plantilla')

@section('titulomain', 'Usuarios/Editar')

@section('contenido')

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container-formulario">
    <div class="card formulario">
        <h2>Editar Usuario</h2>
        <form action="{{route('usuario.update', $usuario->id_usuario)}}" enctype="multipart/form-data" method="POST">
            {{-- agregar directiva para que se genere un token --}}
            @csrf
            @method('PATCH')
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required value={{$usuario->nombre}} class="form-control">
            </div>
            <!-- Campo Apellido -->
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required value={{$usuario->apellido}} class="form-control">
            </div>
            <!-- Imagen actual -->
            <div class="form-group">
                <label>Imagen actual</label>
                <div class="image-preview">
                    <img src="{{ asset($usuario->imagen) }}" alt="Imagen actual" width="96" style="border-radius: 12px;">
                </div>
            </div>
            <!-- Campo Imagen -->
            <div class="form-group">
                <label for="imagen">Cambiar imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" class="form-control">
                <small class="text-muted">Si no selecciona archivo, se mantiene la imagen actual.</small>
            </div>
            <!-- Campo Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value={{$usuario->email}} class="form-control">
            </div>
            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <!-- Campo Rol -->
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required class="form-control">
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ old('rol', $usuario->rolRelacion->id_rol ?? '') == $rol->id_rol ? 'selected' : '' }}>{{ $rol->rol }}</option>
                    @endforeach
                </select>
                <button type="submit">Actualizar Usuario</button>
            </div>
        </form>

    </div>

</div>

@endsection