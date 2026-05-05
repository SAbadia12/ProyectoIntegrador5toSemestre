@extends("layouts.plantilla")

@section("titulomain")
Zonas
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
        <form action="{{ route('zonas.index') }}" method="GET" class="form-filtros">
            {{-- filtro por nombre --}}
            <input type="text" name="buscar" placeholder="Buscar zona" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('zonas.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('zonas.create')}}" class="nav-link btn-agregar">Agregar Zona</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/zonas') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Zona</th>
                    <th>Tipo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Zonas">
                @foreach ($zonas as $zona)
                <tr>
                    <td>{{$zona->id_zona}}</td>
                    <td>{{$zona->zona}}</td>
                    <td>{{$zona->zonasTipo->tipo ?? 'Sin tipo' }}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('zonas.edit', $zona)}}">
                                <img src="img/lapiz.png" alt="">
                            </a>
                            <form action="{{route('zonas.destroy', $zona)}}" method="POST" onsubmit="return confirmarEliminacion()">
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
            return confirm('¿Seguro deseas eliminar esta zona?');
        }
    </script>

    {{ $zonas->links('pagination::bootstrap-4') }}
    @endsection