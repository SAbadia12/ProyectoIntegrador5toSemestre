@extends('layouts.plantilla')

@section('titulomain', 'Zonas/Editar')

@section('contenido')

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="container-formulario">
    <div class="card formulario">
        <h2>Editar Zona</h2>
        <form action="{{route('zonas.update', $zonas->id_zona)}}" method="POST">
            @csrf
            @method('PATCH')
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="zona">Zona</label>
                <input type="text" id="zona" name="zona" required value="{{$zonas->zona}}" class="form-control" placeholder="Ingrese el nombre de la zona">
            </div>
            <!-- Campo Tipo -->
            <div class="form-group">
                <label for="tipo_zona">Tipo de Zona</label>
                <select id="tipo_zona" name="tipo_zona" required class="form-control">
                    <option value="">Seleccione un tipo</option>
                    @foreach($zonas_tipos as $tipo)
                        <option value="{{ $tipo->id_tipo }}" {{ old('tipo_zona', $zonas->zonasTipo->id_tipo ?? '') == $tipo->id_tipo ? 'selected' : '' }}>{{ $tipo->tipo }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Botón Actualizar -->
            <div class="form-group">
                <button type="submit">Actualizar Zona</button>
            </div>
        </form>

    </div>

</div>

@endsection