@extends("layouts.plantilla")

@section("titulomain")
Subzonas
@endsection

@section("contenido")

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
        <form action="{{ route('subzonas.index') }}" method="GET" class="form-filtros">
            <input type="text" name="buscar" placeholder="Buscar subzona" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('subzonas.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{route('subzonas.create')}}" class="nav-link btn-agregar">Agregar Subzona</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/subzonas') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subzona</th>
                    <th>Zona</th>
                    <th>Subtipo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Subzonas">
                @foreach ($subzonas as $subzona)
                <tr>
                    <td>{{ $subzona->id_subzona }}</td>
                    <td>{{ $subzona->subzona }}</td>
                    <td>{{ $subzona->zona->zona ?? 'Sin zona' }}</td>
                    <td>{{ $subzona->subzonasTipo->subtipo ?? 'Sin subtipo' }}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('subzonas.edit', $subzona)}}">
                                <img src="img/lapiz.png" alt="Editar">
                            </a>
                            <form action="{{route('subzonas.destroy', $subzona)}}" method="POST" onsubmit="return confirmarEliminacion()">
                                @csrf
                                @method('DELETE')
                                <input type="image" src="img/basura.png" alt="Eliminar">
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
            return confirm('¿Seguro deseas eliminar esta subzona?');
        }
    </script>

    {{ $subzonas->links('pagination::bootstrap-4') }}

@endsection