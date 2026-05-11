@extends('layouts.plantilla')

@section('titulomain', 'Delitos/Agregar')

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
        <h2>Crear Delito</h2>
        <form action="{{route('delitos.store')}}" method="POST" id="delitoForm">
            @csrf
            <!-- Campo Tipo -->
            <div class="form-group">
                <label for="tipo">Tipo de Delito</label>
                <input type="text" id="tipo" name="tipo" required class="form-control" placeholder="Ej: Robo, Hurto, Homicidio" value="{{old('tipo')}}">
            </div>

            <!-- Campo Gravedad -->
            <div class="form-group">
                <label for="gravedad">Gravedad</label>
                <select id="gravedad" name="gravedad" required class="form-control">
                    <option value="">Seleccione una gravedad</option>
                    <option value="1" {{old('gravedad') == 1 ? 'selected' : ''}}>Leve</option>
                    <option value="2" {{old('gravedad') == 2 ? 'selected' : ''}}>Medio</option>
                    <option value="3" {{old('gravedad') == 3 ? 'selected' : ''}}>Grave</option>
                </select>
            </div>

            <!-- Campo Descripción -->
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Describa detalles adicionales (opcional)" rows="4">{{old('descripcion')}}</textarea>
            </div>

            <!-- Ubicaciones Dinámicas -->
            <div class="form-group">
                <label>Ubicaciones donde ocurrió</label>
                <div id="ubicacionesContainer">
                    <div class="ubicacion-item">
                        <select name="ubicaciones[0][id_ubicacion]" class="form-control">
                            <option value="">Seleccione una ubicación</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id_ubicacion }}">{{ $ubicacion->direccion }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="ubicaciones[0][fecha]" class="form-control">
                        <button type="button" class="btn btn-danger removeUbicacion" style="display:none;">Eliminar</button>
                    </div>
                </div>
                <button type="button" id="addUbicacion" class="btn btn-secondary">+ Agregar Ubicación</button>
            </div>

            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Guardar Delito</button>
            </div>
        </form>
        
    </div>
    
  </div>

<script>
    let ubicacionIndex = 1;

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
        items.forEach((item, index) => {
            const btn = item.querySelector('.removeUbicacion');
            btn.style.display = items.length > 1 ? 'inline-block' : 'none';
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
