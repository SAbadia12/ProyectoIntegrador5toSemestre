@extends('layouts.plantilla')

@section('titulomain', 'Estaciones/Agregar')

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
        <h2>Crear Estación de Policía</h2>
        <form action="{{route('estaciones.store')}}" method="POST">
            @csrf
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required class="form-control" placeholder="Ej: Estación Centro, Estación Sur" value="{{old('nombre')}}">
            </div>

            <!-- Campo Dirección -->
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required class="form-control" placeholder="Dirección completa" value="{{old('direccion')}}">
            </div>

            <!-- Campo Teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Número de teléfono" value="{{old('telefono')}}">
            </div>

            <!-- Campo Latitud -->
            <div class="form-group">
                <label for="latitud">Latitud</label>
                <input type="number" id="latitud" name="latitud" step="0.000001" class="form-control" placeholder="Ej: 3.4372" value="{{old('latitud')}}">
            </div>

            <!-- Campo Longitud -->
            <div class="form-group">
                <label for="longitud">Longitud</label>
                <input type="number" id="longitud" name="longitud" step="0.000001" class="form-control" placeholder="Ej: -76.5197" value="{{old('longitud')}}">
            </div>

            <!-- Campo Subzona -->
            <div class="form-group">
                <label for="id_subzona">Subzona</label>
                <select id="id_subzona" name="id_subzona" required class="form-control">
                    <option value="">Seleccione una subzona</option>
                    @foreach($subzonas as $subzona)
                        <option value="{{ $subzona->id_subzona }}" {{old('id_subzona') == $subzona->id_subzona ? 'selected' : ''}}>{{ $subzona->subzona }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar Estación</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection
