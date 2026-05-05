@extends('layouts.plantilla')

@section('titulomain', 'Subtipos de Zona/Agregar')

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
        <h2>Crear Subtipo de Zona</h2>
        <form action="{{route('subzonas_tipo.store')}}" method="POST">
            @csrf
            <!-- Campo Subtipo -->
            <div class="form-group">
                <label for="subtipo">Subtipo</label>
                <input type="text" id="subtipo" name="subtipo" required class="form-control" placeholder="Ingrese el subtipo de zona">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar Subtipo</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection