@extends("layouts.plantilla")

@section("titulomain")
Subtipos de Zona
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
        <form action="{{ route('subzonas_tipo.index') }}" method="GET" class="form-filtros">
            {{-- filtro por subtipo --}}
            <input type="text" name="buscar" placeholder="Buscar subtipo" value="{{ request('buscar') }}" class="filtro-input">
            <button type="submit" class="nav-link btn-filtrar">Filtrar</button>
            <a href="{{ route('subzonas_tipo.index') }}" class="nav-link btn-filtrar">Limpiar Filtros</a>
        </form>
        <ul class="nav-menu">

            <li class="nav-item">
                <a href="{{route('subzonas_tipo.create')}}" class="nav-link btn-agregar">Agregar Subtipo</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('export/subzonas_tipo') }}" class="nav-link btn-agregar" target="_blank">Generar PDF</a>
            </li>
        </ul>
    </nav>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subtipo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody class="tabla-SubzonasTipo">
                @foreach ($subzonas_tipos as $subzonas_tipo)
                <tr>
                    <td>{{$subzonas_tipo->id_subtipo}}</td>
                    <td>{{$subzonas_tipo->subtipo}}</td>
                    <td>
                        <div class="opciones-cell">
                            <a href="{{route('subzonas_tipo.edit', $subzonas_tipo)}}">
                                <img src="img/lapiz.png" alt="">
                            </a>
                            <form action="{{route('subzonas_tipo.destroy', $subzonas_tipo)}}" method="POST" onsubmit="return confirmarEliminacion()">
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
            return confirm('¿Seguro deseas eliminar este subtipo de zona?');
        }
    </script>

    {{ $subzonas_tipos->links('pagination::bootstrap-4') }}
    @endsection