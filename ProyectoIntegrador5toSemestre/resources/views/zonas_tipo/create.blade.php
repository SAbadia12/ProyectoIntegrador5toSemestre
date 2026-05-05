@extends('layouts.plantilla')

@section('titulomain', 'Tipos de Zona/Agregar')

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
        <h2>Crear Tipo de Zona</h2>
        <form action="{{route('zonas_tipo.store')}}" method="POST">
            @csrf
            <!-- Campo Tipo -->
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" id="tipo" name="tipo" required class="form-control" placeholder="Ingrese el tipo de zona">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar Tipo</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection