@extends('layouts.plantilla')

@section('titulomain', 'Puntos Cardinales/Editar')

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
        <h2>Editar Punto Cardinal</h2>
        <form action="{{route('cardinal.update', $cardinal->id_punto_cardinal)}}" enctype="multipart/form-data" method="POST">
            {{-- agregar directica para qu se genere un token --}}
            @csrf 
            @method('PATCH')
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required value={{$cardinal->nombre}} class="form-control">
            </div>
            
            <!-- Botón Guardar -->
            <div class="form-group">
                <button type="submit">Actualizar Punto Cardinal</button>
            </div>
        </form>
        
    </div>
    
    </div>

@endsection