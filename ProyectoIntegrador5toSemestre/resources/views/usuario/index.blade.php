@extends("layouts.plantilla")

@section("titulomain")
Usuarios
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
        <form action="{{ route('usuario.index') }}" method="GET" class="form-filtros">
            {{-- filtro por nombre --}}
            <input type="text" name="buscar" placeholder="Buscar usuario" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('usuario.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('usuario.create')}}" class="nav-link btn-agregar">Agregar usuario</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/usuario') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    {{-- Cambio 1: envolver table en table-wrapper --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Imagen</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-Usuario">
                @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{$usuario->id_usuario}}</td>
                    <td>{{$usuario->nombre}}</td>
                    <td>{{$usuario->apellido}}</td>
                    <td><img src="{{ asset($usuario->imagen) }}" alt="Imagen" width="50"></td>
                    <td>{{$usuario->email}}</td>
                    <td>{{ $usuario->rolRelacion->rol ?? 'Sin rol' }}</td>
                    <td>
                        @if($usuario->activo)
                            <span class="badge-activo" style="background:#28a745;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">Activo</span>
                        @else
                            <span class="badge-inactivo" style="background:#dc3545;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('usuario.edit', $usuario)}}" title="Editar">
                                <img src="img/lapiz.png" alt="Editar">
                            </a>

                            {{-- RF16: Botón activar/desactivar --}}
                            <form action="{{ route('usuario.toggleActivo', $usuario) }}" method="POST" style="display:inline;" onsubmit="return confirmarToggle({{ $usuario->activo ? 'true' : 'false' }})">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-toggle" title="{{ $usuario->activo ? 'Desactivar' : 'Activar' }}" style="background:none;border:none;cursor:pointer;padding:0;">
                                    @if($usuario->activo)
                                        <span style="color:#dc3545;font-weight:bold;font-size:18px;">⏻</span>
                                    @else
                                        <span style="color:#28a745;font-weight:bold;font-size:18px;">✓</span>
                                    @endif
                                </button>
                            </form>

                            <form action="{{route('usuario.destroy', $usuario)}}" method="POST" onsubmit="return confirmarEliminacion()" style="display:inline;">
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

    {{-- Cambio 2: script FUERA del loop y del td --}}
    <script>
        function confirmarEliminacion() {
            return confirm('¿Seguro deseas eliminar?');
        }
        function confirmarToggle(estaActivo) {
            const accion = estaActivo ? 'desactivar' : 'activar';
            return confirm(`¿Seguro deseas ${accion} este usuario?`);
        }
    </script>

    {{ $usuarios->links('pagination::bootstrap-4') }}
    @endsection