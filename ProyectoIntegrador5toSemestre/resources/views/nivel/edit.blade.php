@extends('layouts.plantilla')

@section('titulomain', 'Niveles de Riesgo/Editar')

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
        <h2>Editar Nivel de riesgo</h2>
        <form action="{{route('nivel.update', $nivel->id_nivel_riesgo)}}" enctype="multipart/form-data" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf 
            @method('PATCH')
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nivel">Nivel</label>
                <input type="text" id="nivel" name="nivel" required value={{$nivel->nivel}} class="form-control">
            </div>
            <!-- Campo Descripción -->
            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" required value={{$nivel->color}} class="form-control">
            </div>
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Actualizar nivel</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection