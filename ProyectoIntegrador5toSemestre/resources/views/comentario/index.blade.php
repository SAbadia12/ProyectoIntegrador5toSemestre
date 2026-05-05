@extends("layouts.plantilla")

@section("titulomain")
Comentarios — Moderación
@endsection

@section("contenido")

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<section class="container-tabla">

    <nav class="nav-botones">
        <form action="{{ route('comentario.index') }}" method="GET" class="form-filtros">
            <select name="estado" class="filtro-select" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                <option value="aprobado"  {{ request('estado') == 'aprobado'  ? 'selected' : '' }}>Aprobados</option>
                <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazados</option>
            </select>
            <a href="{{ route('comentario.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comentarios as $c)
                <tr>
                    <td>{{ $c->id_comentario }}</td>
                    <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $c->nombre }}</td>
                    <td>{{ $c->email ?? '—' }}</td>
                    <td style="max-width:380px;white-space:normal;">{{ $c->contenido }}</td>
                    <td>
                        @php
                            $colors = [
                                'pendiente' => '#f59e0b',
                                'aprobado'  => '#22c55e',
                                'rechazado' => '#ef4444',
                            ];
                            $bg = $colors[$c->estado] ?? '#94a3b8';
                        @endphp
                        <span style="background:{{ $bg }};color:#fff;padding:3px 10px;border-radius:999px;font-size:.75em;text-transform:uppercase;font-weight:700;">
                            {{ $c->estado }}
                        </span>
                    </td>
                    <td>
                        <div class="opciones-cell" style="display:flex;gap:6px;align-items:center;">
                            @if($c->estado !== 'aprobado')
                                <form action="{{ route('comentario.aprobar', $c) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Aprobar" style="background:#22c55e;color:#fff;border:none;padding:4px 10px;border-radius:6px;cursor:pointer;font-size:.8em;">✓</button>
                                </form>
                            @endif
                            @if($c->estado !== 'rechazado')
                                <form action="{{ route('comentario.rechazar', $c) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Rechazar" style="background:#f59e0b;color:#fff;border:none;padding:4px 10px;border-radius:6px;cursor:pointer;font-size:.8em;">✕</button>
                                </form>
                            @endif
                            <form action="{{ route('comentario.destroy', $c) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar" style="background:#ef4444;color:#fff;border:none;padding:4px 10px;border-radius:6px;cursor:pointer;font-size:.8em;">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:24px;color:#94a3b8;">No hay comentarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $comentarios->links('pagination::bootstrap-4') }}
</section>
@endsection
