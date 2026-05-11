@extends('layouts.plantilla')

@section('titulomain', 'Delitos/Editar')

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
        <h2>Editar Delito</h2>
        <form action="{{route('delitos.update', $delito->id_delito)}}" method="POST" id="delitoForm">
            @csrf
            @method('PATCH')

            <!-- Campo Tipo -->
            <div class="form-group">
                <label for="tipo">Tipo de Delito</label>
                <input type="text" id="tipo" name="tipo" required class="form-control" value="{{$delito->tipo}}">
            </div>

            <!-- Campo Gravedad -->
            <div class="form-group">
                <label for="gravedad">Gravedad</label>
                <select id="gravedad" name="gravedad" required class="form-control">
                    <option value="">Seleccione una gravedad</option>
                    <option value="1" {{old('gravedad', $delito->gravedad) == 1 ? 'selected' : ''}}>Leve</option>
                    <option value="2" {{old('gravedad', $delito->gravedad) == 2 ? 'selected' : ''}}>Medio</option>
                    <option value="3" {{old('gravedad', $delito->gravedad) == 3 ? 'selected' : ''}}>Grave</option>
                </select>
            </div>

            <!-- Campo Descripción -->
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4">{{$delito->descripcion}}</textarea>
            </div>

            <!-- Ubicaciones Dinámicas -->
            <div class="form-group">
                <label>Ubicaciones donde ocurrió</label>
                <div id="ubicacionesContainer">
                    @foreach($delitoUbicaciones as $index => $ubicacion)
                    <div class="ubicacion-item">
                        <select name="ubicaciones[{{$index}}][id_ubicacion]" class="form-control">
                            <option value="">Seleccione una ubicación</option>
                            @foreach($ubicaciones as $ub)
                                <option value="{{ $ub->id_ubicacion }}" {{$ub->id_ubicacion == $ubicacion->id_ubicacion ? 'selected' : ''}}>{{ $ub->direccion }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="ubicaciones[{{$index}}][fecha]" class="form-control" value="{{$ubicacion->pivot->fecha}}">
                        <button type="button" class="btn btn-danger removeUbicacion">Eliminar</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="addUbicacion" class="btn btn-secondary">+ Agregar Ubicación</button>
            </div>

            <!-- Botón Actualizar -->
            <div class="form-group">
                <button type="submit">Actualizar Delito</button>
            </div>
        </form>

    </div>

</div>

<script>
    let ubicacionIndex = {{ $delitoUbicaciones->count() }};

    document.getElementById('addUbicacion').addEventListener('click', function() {
        const container = document.getElementById('ubicacionesContainer');
        const newItem = document.createElement('div');
        newItem.className = 'ubicacion-item';
        newItem.innerHTML = `
            <select name="ubicaciones[${ubicacionIndex}][id_ubicacion]" class="form-control">
                <option value="">Seleccione una ubicación</option>
                @foreach($ubicaciones as $ubicacion)
                    <option value="{{ $ubicacion->id_ubicacion }}">{{ $ubicacion->direccion }}</option>
                @endforeach
            </select>
            <input type="date" name="ubicaciones[${ubicacionIndex}][fecha]" class="form-control">
            <button type="button" class="btn btn-danger removeUbicacion">Eliminar</button>
        `;
        container.appendChild(newItem);
        ubicacionIndex++;
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.ubicacion-item');
        items.forEach((item) => {
            const btn = item.querySelector('.removeUbicacion');
            btn.onclick = function() {
                item.remove();
            };
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeUbicacion')) {
            e.preventDefault();
            e.target.closest('.ubicacion-item').remove();
            updateRemoveButtons();
        }
    });

    updateRemoveButtons();
</script>

@endsection
