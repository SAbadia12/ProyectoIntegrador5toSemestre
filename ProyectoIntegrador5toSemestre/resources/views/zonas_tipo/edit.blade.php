@extends('layouts.plantilla')

@section('titulomain', 'Tipos de Zona/Editar')

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
        <h2>Editar Tipo de Zona</h2>
        <form action="{{route('zonas_tipo.update', $zonas_tipo->id_tipo)}}" method="POST">
            @csrf
            @method('PATCH')
            <!-- Campo Tipo -->
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" id="tipo" name="tipo" required value="{{$zonas_tipo->tipo}}" class="form-control" placeholder="Ingrese el tipo de zona">
            </div>
            <!-- Botón Actualizar -->
            <div class="form-group">
                <button type="submit">Actualizar Tipo</button>
            </div>
        </form>

    </div>

</div>

@endsection