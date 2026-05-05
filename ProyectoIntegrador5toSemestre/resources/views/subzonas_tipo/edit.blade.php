@extends('layouts.plantilla')

@section('titulomain', 'Subtipos de Zona/Editar')

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
        <h2>Editar Subtipo de Zona</h2>
        <form action="{{route('subzonas_tipo.update', $subzonasTipo->id_subtipo)}}" method="POST">
            @csrf
            @method('PATCH')
            <!-- Campo Subtipo -->
            <div class="form-group">
                <label for="subtipo">Subtipo</label>
                <input type="text" id="subtipo" name="subtipo" required value="{{$subzonasTipo->subtipo}}" class="form-control" placeholder="Ingrese el subtipo de zona">
            </div>
            <!-- Botón Actualizar -->
            <div class="form-group">
                <button type="submit">Actualizar Subtipo</button>
            </div>
        </form>

    </div>

</div>

@endsection