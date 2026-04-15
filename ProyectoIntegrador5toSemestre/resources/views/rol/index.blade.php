@extends("layouts.plantilla")

@section("titulomain")
Roles
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
        <form action="{{ route('rol.index') }}" method="GET" class="form-filtros">
            {{-- filtro por rol --}}
            <input type="text" name="buscar" placeholder="Buscar rol" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('rol.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('rol.create')}}" class="nav-link btn-agregar">Agregar rol</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/rol') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody class="tabla-Rol">

            @foreach ($roles as $rol)
            <tr>
                <td>{{$rol->id_rol}}</td>
                <td>{{$rol->rol}}</td>
                <td>
                    <a href="{{route('rol.show', $rol)}}">
                        <img src="img/view.png" alt="">
                    </a>




                    <a href="{{route('rol.edit', $rol)}}">
                        <img src="img/lapiz.png" alt="">
                    </a>



                    <form action="{{route('rol.destroy', $rol)}}" method="POST" onsubmit="return confimarEliminacion()">

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
    {{ $roles->links('pagination::bootstrap-4') }}
    @endsection