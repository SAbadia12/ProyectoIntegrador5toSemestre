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
    <h2 class="titulo-tabla"> Niveles de Riesgo</h2>

    <nav class="nav-botones">
        <ul class="nav-menu">      
      
            <li class="nav-item">
                <a href="{{route('nivel.create')}}" class="nav-link btn-agregar">Agregar nivel de riesgo</a>
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