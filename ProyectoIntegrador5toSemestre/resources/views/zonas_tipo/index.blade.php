@extends("layouts.plantilla")

@section("titulomain")
Tipos de Zona
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
        <form action="{{ route('zonas_tipo.index') }}" method="GET" class="form-filtros">
            {{-- filtro por tipo --}}
            <input type="text" name="buscar" placeholder="Buscar tipo" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('zonas_tipo.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('zonas_tipo.create')}}" class="nav-link btn-agregar">Agregar Tipo</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/zonas_tipo') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-ZonasTipo">
                @foreach ($zonas_tipos as $zonas_tipo)
                <tr>
                    <td>{{$zonas_tipo->id_tipo}}</td>
                    <td>{{$zonas_tipo->tipo}}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('zonas_tipo.edit', $zonas_tipo)}}">
                                <img src="img/lapiz.png" alt="">
                            </a>
                            <form action="{{route('zonas_tipo.destroy', $zonas_tipo)}}" method="POST" onsubmit="return confirmarEliminacion()">
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
            return confirm('¿Seguro deseas eliminar este tipo de zona?');
        }
    </script>

    {{ $zonas_tipos->links('pagination::bootstrap-4') }}
    @endsection