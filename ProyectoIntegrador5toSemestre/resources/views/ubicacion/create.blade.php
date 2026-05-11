@extends('layouts.plantilla')

@section('titulomain', 'Ubicaciones / Agregar')

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
        <h2>Crear Ubicación</h2>
        <form action="{{ route('ubicacion.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" required class="form-control" placeholder="Ej: Cra. 5 #10-20">
            </div>

            <div class="form-group">
                <label for="latitud">Latitud (opcional, para mapa)</label>
                <input type="number" step="0.0000001" id="latitud" name="latitud" value="{{ old('latitud') }}" class="form-control" placeholder="Ej: 3.4516">
            </div>

            <div class="form-group">
                <label for="longitud">Longitud (opcional, para mapa)</label>
                <input type="number" step="0.0000001" id="longitud" name="longitud" value="{{ old('longitud') }}" class="form-control" placeholder="Ej: -76.5320">
            </div>

            <div class="form-group">
                <label for="id_nivel">Nivel de Riesgo</label>
                <select id="id_nivel" name="id_nivel" required class="form-control">
                    <option value="">Seleccione un nivel de riesgo</option>
                    @foreach($niveles as $n)
                        <option value="{{ $n->id_nivel_riesgo }}" {{ old('id_nivel') == $n->id_nivel_riesgo ? 'selected' : '' }}>
                            {{ $n->nivel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_punto_cardinal">Punto Cardinal</label>
                <select id="id_punto_cardinal" name="id_punto_cardinal" required class="form-control">
                    <option value="">Seleccione un punto cardinal</option>
                    @foreach($puntosCardinales as $p)
                        <option value="{{ $p->id_punto_cardinal }}" {{ old('id_punto_cardinal') == $p->id_punto_cardinal ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_subzona">Subzona</label>
                <select id="id_subzona" name="id_subzona" required class="form-control">
                    <option value="">Seleccione una subzona</option>
                    @foreach($subzonas as $s)
                        <option value="{{ $s->id_subzona }}" {{ old('id_subzona') == $s->id_subzona ? 'selected' : '' }}>
                            {{ $s->subzona }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Guardar ubicación</button>
            </div>
        </form>
    </div>
</div>

@endsection
