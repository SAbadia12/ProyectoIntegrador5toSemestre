@extends('layouts.plantilla')

@section('titulomain', 'Usuarios/agregar')

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

  <div class= "container-formulario">
    <div class="card formulario">
        <h2>Crear Usuario</h2>
        <form action="{{route('usuario.store')}}" method="POST" enctype="multipart/form-data">
            {{-- agregar directiva para que se genere un token --}}
            @csrf
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required  class="form-control">
            </div>
            <!-- Campo Apellido -->
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required class="form-control">
            </div>
            <!-- Campo Imagen -->
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required class="form-control">
                <small class="text-muted">Seleccione una imagen JPG, PNG o GIF desde su dispositivo.</small>
            </div>
            <!-- Campo Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>
            <!-- Campo Rol -->
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required class="form-control">
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ old('rol') == $rol->id_rol ? 'selected' : '' }}>{{ $rol->rol }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar usuario</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection