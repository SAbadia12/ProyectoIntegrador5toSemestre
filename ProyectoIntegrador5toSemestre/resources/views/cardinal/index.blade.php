@extends("layouts.plantilla")

@section("titulomain")
Punto Cardinal
@endsection

@section("contenido")

    {{-- Realimentación con los mensajes al usuario --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
<section class="container-tabla">
    <h2 class="titulo-tabla"> Puntos Cardinales</h2>

    <nav class="nav-botones">
        <ul class="nav-menu">      
      
            <li class="nav-item">
                <a href="{{route('cardinal.create')}}" class="nav-link btn-agregar">Agregar Punto Cardinal</a>
            </li>      
         
        </ul>
    </nav>
    
    <table >
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>   
                <th>Opciones</th>
            </tr>
        </thead>
       <tbody class="tabla-PuntoCardinal">

    @foreach ($puntoCardinal as $cardinal)
       <tr>
         <td>{{$cardinal->id_punto_cardinal}}</td>
         <td>{{$cardinal->nombre}}</td>   
            <td >
                <a href="{{route('cardinal.show', $cardinal)}}">
                   <img src="img/view.png" alt=""> 
                </a>

             
                      
                 
                   <a href="{{route('cardinal.edit', $cardinal)}}">
                   <img src="img/lapiz.png" alt="">
                   </a>
                             
                                        
               
                    <form action="{{route('cardinal.destroy', $cardinal)}}" method="POST" onsubmit="return confimarEliminacion()">

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