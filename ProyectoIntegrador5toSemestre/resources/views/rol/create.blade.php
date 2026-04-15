@extends('layouts.plantilla')

@section('titulomain', 'Roles/agregar')

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
        <h2>Crear Rol</h2>
        <form action="{{route('rol.store')}}" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="rol">Rol</label>
                <input type="text" id="rol" name="rol" required  class="form-control">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar rol</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection