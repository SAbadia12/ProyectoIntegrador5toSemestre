@extends('layouts.plantilla')

@section('titulomain', 'Subzonas/Agregar')

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
        <h2>Crear Subzona</h2>
        <form action="{{route('subzonas.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="subzona">Subzona</label>
                <input type="text" id="subzona" name="subzona" required class="form-control" placeholder="Ingrese el nombre de la subzona" value="{{ old('subzona') }}">
            </div>
            <div class="form-group">
                <label for="id_zona">Zona</label>
                <select id="id_zona" name="id_zona" required class="form-control">
                    <option value="">Seleccione una zona</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id_zona }}" {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>{{ $zona->zona }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_subzona">Subtipo de Zona</label>
                <select id="tipo_subzona" name="tipo_subzona" required class="form-control">
                    <option value="">Seleccione un subtipo</option>
                    @foreach($subzonas_tipos as $subtipo)
                        <option value="{{ $subtipo->id_subtipo }}" {{ old('tipo_subzona') == $subtipo->id_subtipo ? 'selected' : '' }}>{{ $subtipo->subtipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Guardar Subzona</button>
            </div>
        </form>
    </div>
  </div>

@endsection