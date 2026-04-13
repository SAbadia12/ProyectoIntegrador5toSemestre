@extends('layouts.plantilla')

@section('titulomain', 'Niveles de Riesgo/agregar')

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
        <h2>Crear Nivel de riesgo</h2>
        <form action="{{route('nivel.store')}}" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nivel">Nivel</label>
                <input type="text" id="nivel" name="nivel" required  class="form-control">
            </div>
            <!-- Campo Descripción -->
            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" required  class="form-control">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar nivel</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection