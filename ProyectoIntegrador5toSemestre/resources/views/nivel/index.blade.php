@extends("layouts.plantilla")

@section("titulomain")
Niveles de Riesgo
@endsection

@section("contenido")

    {{-- Realimentación con los mensajes al usuario --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
<section class="container-tabla">

    <nav class="nav-botones">
         {{-- formulario para filtros y busqueda --}}
        <form action="{{ route('nivel.index') }}" method="GET" class="form-filtros">
            {{-- Filtro por color --}}
            <select name="nivel" class="filtro-select">
                <option value="">Todos los colores</option>
                @foreach ($nivelRiesgo as $nivel)
                <option value="{{ $nivel->color }}" {{ request('nivel') == $nivel->color ? 'selected' : '' }}>
                    {{ $nivel->color }}
                </option>
                @endforeach
            </select>

            {{-- filtro por nivel --}}
            <input type="text" name="buscar" placeholder="Buscar nivel" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('nivel.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">      
      
            <li class="nav-item">
                <a href="{{route('nivel.create')}}" class="nav-link btn-agregar">Agregar nivel de riesgo</a>
            </li>      
            <li class="nav-item">
                <a href="{{ url('export/nivel_riesgo') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>
    
    <table >
        <thead>
            <tr>
                <th>ID</th>
                <th>Nivel</th>
                <th>Color</th>            
                <th>Opciones</th>
            </tr>
        </thead>
       <tbody class="tabla-NivelRiesgo">

    @foreach ($nivelRiesgo as $nivel)
       <tr>
         <td>{{$nivel->id_nivel_riesgo}}</td>
         <td>{{$nivel->nivel}}</td>
         <td>{{$nivel->color}}</td>      
            <td >
                <a href="{{route('nivel.show', $nivel)}}">
                   <img src="img/view.png" alt=""> 
                </a>

             
                      
                 
                   <a href="{{route('nivel.edit', $nivel)}}">
                   <img src="img/lapiz.png" alt="">
                   </a>
                             
                                        
               
                    <form action="{{route('nivel.destroy', $nivel)}}" method="POST" onsubmit="return confimarEliminacion()">

                    {{-- permite gemrar el token para enviar por post --}}
                    @csrf
                    {{-- agregar metodo delete --}}
                    @method('DELETE')
                    <input type="image"src="img/basura.png"></input>

                     </form>
               
                 <script>
                    function confimarEliminacion() {
                        return confirm('¿Seguro deseas eliminar?'); // Muestra el mensaje de confirmación
                    }
                </script>
         </td>
       </tr>
           
       @endforeach

       </tbody>

    </table>
@endsection