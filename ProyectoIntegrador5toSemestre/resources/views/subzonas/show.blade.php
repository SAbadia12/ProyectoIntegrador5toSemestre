@extends('layouts.plantilla')

@section('titulomain', 'Subzonas/Detalle')

@section('contenido')

<div class="container-formulario">
    <div class="card formulario">
        <h2>Detalle de Subzona</h2>
        <div class="form-group">
            <label>ID</label>
            <p>{{ $subzonas->id_subzona }}</p>
        </div>
        <div class="form-group">
            <label>Subzona</label>
            <p>{{ $subzonas->subzona }}</p>
        </div>
        <div class="form-group">
            <label>Zona</label>
            <p>{{ $subzonas->zona->nombre ?? 'Sin zona' }}</p>
        </div>
        <div class="form-group">
            <label>Subtipo</label>
            <p>{{ $subzonas->subzonasTipo->subtipo ?? 'Sin subtipo' }}</p>
        </div>
        <div class="form-group">
            <a href="{{ route('subzonas.index') }}" class="btn-agregar">Volver a Subzonas</a>
        </div>
    </div>
</div>

@endsection