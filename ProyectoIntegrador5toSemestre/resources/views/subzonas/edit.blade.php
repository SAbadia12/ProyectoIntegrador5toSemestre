@extends('layouts.plantilla')

@section('titulomain', 'Subzonas/Editar')

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
        <h2>Editar Subzona</h2>
        <form action="{{route('subzonas.update', $subzonas->id_subzona)}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="subzona">Subzona</label>
                <input type="text" id="subzona" name="subzona" required value="{{ old('subzona', $subzonas->subzona) }}" class="form-control" placeholder="Ingrese el nombre de la subzona">
            </div>
            <div class="form-group">
                <label for="id_zona">Zona</label>
                <select id="id_zona" name="id_zona" required class="form-control">
                    <option value="">Seleccione una zona</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id_zona }}" {{ old('id_zona', $subzonas->id_zona) == $zona->id_zona ? 'selected' : '' }}>{{ $zona->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_subzona">Subtipo de Zona</label>
                <select id="tipo_subzona" name="tipo_subzona" required class="form-control">
                    <option value="">Seleccione un subtipo</option>
                    @foreach($subzonas_tipos as $subtipo)
                        <option value="{{ $subtipo->id_subtipo }}" {{ old('tipo_subzona', $subzonas->tipo_subzona) == $subtipo->id_subtipo ? 'selected' : '' }}>{{ $subtipo->subtipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Actualizar Subzona</button>
            </div>
        </form>
    </div>
</div>

@endsection