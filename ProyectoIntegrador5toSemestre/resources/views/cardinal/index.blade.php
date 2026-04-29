@extends("layouts.plantilla")

@section("titulomain")
Puntos Cardinales
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
        <form action="{{ route('cardinal.index') }}" method="GET" class="form-filtros">

            {{-- filtro por nivel --}}
            <input type="text" name="buscar" placeholder="Buscar Punto Cardinal" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">BUSCAR</button>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('cardinal.create')}}" class="nav-link btn-agregar">Agregar Punto Cardinal</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/punto_cardinal') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <table>
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
                <td class="opciones-cell">




                    <a href="{{route('cardinal.edit', $cardinal)}}">
                        <img src="img/lapiz.png" alt="">
                    </a>



                    <form action="{{route('cardinal.destroy', $cardinal)}}" method="POST" onsubmit="return confimarEliminacion()">

                        {{-- permite gemrar el token para enviar por post --}}
                        @csrf
                        {{-- agregar metodo delete --}}
                        @method('DELETE')
                        <input type="image" src="img/basura.png"></input>

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
    {{-- Enlaces de paginación --}}
    {{ $puntoCardinal->links('pagination::bootstrap-4') }}
@endsection