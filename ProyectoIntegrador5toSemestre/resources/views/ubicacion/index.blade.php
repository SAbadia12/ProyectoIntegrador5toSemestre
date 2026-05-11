@extends("layouts.plantilla")

@section("titulomain")
Ubicaciones
@endsection

@section("contenido")

{{-- Mensajes --}}
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
        <form action="{{ route('ubicacion.index') }}" method="GET" class="form-filtros">
            {{-- Filtro por nivel --}}
            <select name="nivel" class="filtro-select">
                <option value="">Todos los niveles</option>
                @foreach ($niveles as $n)
                    <option value="{{ $n->id_nivel_riesgo }}" {{ request('nivel') == $n->id_nivel_riesgo ? 'selected' : '' }}>
                        {{ $n->nivel }}
                    </option>
                @endforeach
            </select>

            {{-- Filtro por subzona --}}
            <select name="subzona" class="filtro-select">
                <option value="">Todas las Subzonas</option>
                @foreach ($subzonas as $s)
                    <option value="{{ $s->id_subzona }}" {{ request('subzona') == $s->id_subzona ? 'selected' : '' }}>
                        {{ $s->subzona }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="buscar" placeholder="Buscar por dirección" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('ubicacion.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('ubicacion.create') }}" class="nav-link btn-agregar">Agregar Ubicación</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/ubicacion') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dirección</th>
                    <th>Nivel de Riesgo</th>
                    <th>Punto Cardinal</th>
                    <th>Zona</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Ubicacion">
                @foreach ($ubicaciones as $ubicacion)
                <tr>
                    <td>{{ $ubicacion->id_ubicacion }}</td>
                    <td>{{ $ubicacion->direccion }}</td>
                    <td>
                        @if($ubicacion->nivel)
                            <span style="display:inline-block;padding:3px 10px;border-radius:6px;color:#fff;background:{{ $ubicacion->nivel->color ?? '#94a3b8' }};font-size:.85em;">
                                {{ $ubicacion->nivel->nivel }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $ubicacion->puntoCardinal->nombre ?? '—' }}</td>
                    <td>{{ $ubicacion->subzona->subzona ?? '—' }}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{ route('ubicacion.edit', $ubicacion) }}" title="Editar">
                                <img src="{{ asset('img/lapiz.png') }}" alt="Editar">
                            </a>
                            <form action="{{ route('ubicacion.destroy', $ubicacion) }}" method="POST" onsubmit="return confirmarEliminacion()" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="image" src="{{ asset('img/basura.png') }}" alt="Eliminar">
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
            return confirm('¿Seguro deseas eliminar esta ubicación?');
        }
    </script>

    {{ $ubicaciones->links('pagination::bootstrap-4') }}
</section>
@endsection
