@extends('layouts.plantilla')

@section('titulomain', 'Puntos Cardinales /agregar')

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
        <h2>Crear Punto Cardinal</h2>
        <form action="{{route('cardinal.store')}}" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required  class="form-control">
            </div>
            
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar Punto Cardinal</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection