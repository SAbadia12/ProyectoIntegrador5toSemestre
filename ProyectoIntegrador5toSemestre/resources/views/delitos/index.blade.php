@extends("layouts.plantilla")

@section("titulomain")
Delitos
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
        <form action="{{ route('delitos.index') }}" method="GET" class="form-filtros">
            {{-- filtro por nombre --}}
            <input type="text" name="buscar" placeholder="Buscar delito" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('delitos.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('delitos.create')}}" class="nav-link btn-agregar">Agregar Delito</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Gravedad</th>
                    <th>Ubicaciones</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Delitos">
                @foreach ($delitos as $delito)
                <tr>
                    <td>{{$delito->id_delito}}</td>
                    <td>{{$delito->tipo}}</td>
                    <td>
                        @if($delito->gravedad == 1)
                            <span class="badge badge-success">Leve</span>
                        @elseif($delito->gravedad == 2)
                            <span class="badge badge-warning">Medio</span>
                        @else
                            <span class="badge badge-danger">Grave</span>
                        @endif
                    </td>
                    <td>{{ $delito->ubicaciones->count() }}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('delitos.edit', $delito)}}">
                                <img src="img/lapiz.png" alt="">
                            </a>
                            <form action="{{route('delitos.destroy', $delito)}}" method="POST" onsubmit="return confirmarEliminacion()">
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
            return confirm('¿Seguro deseas eliminar este delito?');
        }
    </script>

    {{ $delitos->links('pagination::bootstrap-4') }}
    @endsection
