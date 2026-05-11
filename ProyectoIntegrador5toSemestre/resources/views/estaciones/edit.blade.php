@extends('layouts.plantilla')

@section('titulomain', 'Estaciones/Editar')

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
        <h2>Editar Estación de Policía</h2>
        <form action="{{route('estaciones.update', $estacion->id_estacion)}}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required class="form-control" value="{{$estacion->nombre}}">
            </div>

            <!-- Campo Dirección -->
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required class="form-control" value="{{$estacion->direccion}}">
            </div>

            <!-- Campo Teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{$estacion->telefono}}">
            </div>

            <!-- Campo Latitud -->
            <div class="form-group">
                <label for="latitud">Latitud</label>
                <input type="number" id="latitud" name="latitud" step="0.000001" class="form-control" value="{{$estacion->latitud}}">
            </div>

            <!-- Campo Longitud -->
            <div class="form-group">
                <label for="longitud">Longitud</label>
                <input type="number" id="longitud" name="longitud" step="0.000001" class="form-control" value="{{$estacion->longitud}}">
            </div>

            <!-- Campo Subzona -->
            <div class="form-group">
                <label for="id_subzona">Subzona</label>
                <select id="id_subzona" name="id_subzona" required class="form-control">
                    <option value="">Seleccione una subzona</option>
                    @foreach($subzonas as $subzona)
                        <option value="{{ $subzona->id_subzona }}" {{old('id_subzona', $estacion->id_subzona) == $subzona->id_subzona ? 'selected' : ''}}>{{ $subzona->subzona }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Actualizar -->
            <div class="form-group">
                <button type="submit">Actualizar Estación</button>
            </div>
        </form>

    </div>

</div>

@endsection
