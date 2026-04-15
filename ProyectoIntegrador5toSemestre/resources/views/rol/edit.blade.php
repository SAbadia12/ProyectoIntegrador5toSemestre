@extends('layouts.plantilla')

@section('titulomain', 'Roles/Editar')

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
        <h2>Editar Rol</h2>
        <form action="{{route('rol.update', $rol->id_rol)}}" enctype="multipart/form-data" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf
            @method('PATCH')
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="rol">Rol</label>
                <input type="text" id="rol" name="rol" required value={{$rol->rol}} class="form-control">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Actualizar rol</button>
            </div>
        </form>

    </div>

</div>

@endsection