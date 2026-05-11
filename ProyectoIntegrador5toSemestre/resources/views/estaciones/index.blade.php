@extends("layouts.plantilla")

@section("titulomain")
Estaciones de Policía
@endsection

@section("contenido")

{{-- Realimentación con los mensajes al usuario --}}
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<section class="container-tabla">

    <nav class="nav-botones">
        {{-- formulario para filtros y busqueda --}}
        <form action="{{ route('estaciones.index') }}" method="GET" class="form-filtros">
            {{-- filtro por nombre --}}
            <input type="text" name="buscar" placeholder="Buscar estación" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('estaciones.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('estaciones.create')}}" class="nav-link btn-agregar">Agregar Estación</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Subzona</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Estaciones">
                @foreach ($estaciones as $estacion)
                <tr>
                    <td>{{$estacion->id_estacion}}</td>
                    <td>{{$estacion->nombre}}</td>
                    <td>{{$estacion->direccion}}</td>
                    <td>{{$estacion->telefono ?? 'N/A'}}</td>
                    <td>{{$estacion->subzona->subzona ?? 'Sin subzona'}}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('estaciones.edit', $estacion)}}">
                                <img src="img/lapiz.png" alt="">
                            </a>
                            <form action="{{route('estaciones.destroy', $estacion)}}" method="POST" onsubmit="return confirmarEliminacion()">
                                @csrf
                                @method('DELETE')
                                <input type="image" src="img/basura.png">
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function confirmarEliminacion() {
            return confirm('¿Seguro deseas eliminar esta estación?');
        }
    </script>

    {{ $estaciones->links('pagination::bootstrap-4') }}
    @endsection
