<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logoSISC.jpeg') }}" type="image/jpeg">
    <title>SISC - Admin Web</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos-tablas.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos-formularios.css')}}">


</head>

<body>
    <!-- slidebar   -->
    <aside class="slidebar" id="slidebar">
        <div class="slidebar-head">
            <a href="#" class="logo">
                <img src="{{asset('img/logoSISC.jpeg')}}" alt="Logo" class="logo-img">
                <div class="logo-copy">
                    <span class="logo-title">SISC</span>
                    <small class="logo-subtitle">Administrador Web</small>
                </div>
            </a>
            <div class="profile-card">
                <img src="{{ asset(session('logged_user_image')) }}" alt="avatar">
                <div class="profile-info">
                    <span>{{ session('logged_user_name', 'logged_user_id') }} {{ session('logged_user_lastname') }}</span>
                    <small>Bienvenido</small>
                </div>
            </div>
        </div>
        <nav class="slidebar-nav">
            <a href="{{ route('plantilla') }}" class="element-slidebar-btn {{ request()->routeIs('plantilla') ? 'active' : '' }}">
                <img src="{{asset('img/Dashboard.png')}}" alt="Dashboard">
                <span>Dashboard</span>
            </a>

            <a href="{{ route('nivel.index') }}" class="element-slidebar-btn {{ request()->routeIs('nivel.*') ? 'active' : '' }}">
                <img src="{{asset('img/Alarma.png')}}" alt="NivelRiesgo">
                <span>Niveles de riesgo</span>
            </a>
            <a href="{{ route('cardinal.index') }}" class="element-slidebar-btn {{ request()->routeIs('cardinal.*') ? 'active' : '' }}">
                <img src="{{asset('img/puntosCardinales.png')}}" alt="PuntoCardinal">
                <span>Puntos Cardinales</span>
            </a>
            <a href="{{ route('rol.index') }}" class="element-slidebar-btn {{ request()->routeIs('rol.*') ? 'active' : '' }}">
                <img src="{{asset('img/Roles.png')}}" alt="Roles">
                <span>Roles</span>
            </a>
            <a href="{{ route('usuario.index') }}" class="element-slidebar-btn {{ request()->routeIs('usuario.*') ? 'active' : '' }}">
                <img src="{{asset('img/Usuarios.png')}}" alt="Usuarios">
                <span>Usuarios</span>
            </a>

            <button type="button" class="accordion-btn" id="zonas-accordion-btn">
                <div class="accordion-header">
                    <img src="{{asset('img/Zonas.png')}}" alt="Config Zonas">
                    <span>Config Zonas</span>
                </div>
                <span class="accordion-icon">›</span>
            </button>
            <div class="accordion-content" id="zonas-accordion-content">
                <a href="{{ route('zonas_tipo.index') }}" class="element-slidebar-btn accordion-item {{ request()->routeIs('zonas_tipo.*') ? 'active' : '' }}">
                    <img src="{{asset('img/Zonas.png')}}" alt="ZonasTipo">
                    <span>Tipos de Zona</span>
                </a>
                <a href="{{ route('zonas.index') }}" class="element-slidebar-btn accordion-item {{ request()->routeIs('zonas.*') ? 'active' : '' }}">
                    <img src="{{asset('img/Zonas.png')}}" alt="Zonas">
                    <span>Zonas</span>
                </a>
                <a href="{{ route('subzonas_tipo.index') }}" class="element-slidebar-btn accordion-item {{ request()->routeIs('subzonas_tipo.*') ? 'active' : '' }}">
                    <img src="{{asset('img/Zonas.png')}}" alt="SubzonasTipo">
                    <span>Subtipos de Zona</span>
                </a>
                <a href="{{ route('subzonas.index') }}" class="element-slidebar-btn accordion-item {{ request()->routeIs('subzonas.*') ? 'active' : '' }}">
                    <img src="{{asset('img/Zonas.png')}}" alt="Subzonas">
                    <span>Subzonas</span>
                </a>
            </div>

            {{-- Ubicaciones (entidad central del MER) --}}
            <a href="{{ route('ubicacion.index') }}" class="element-slidebar-btn {{ request()->routeIs('ubicacion.*') ? 'active' : '' }}">
                <img src="{{asset('img/puntosCardinales.png')}}" alt="Ubicaciones">
                <span>Ubicaciones</span>
            </a>
        </nav>
        <div class="slidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link">Cerrar sesión</button>
            </form>
        </div>
    </aside>
    <!-- main -->
    <main class="main">
        <!-- header -->
        <header class="header">
            <div class="titulo-nav">@yield('titulomain')</div>

            <button id="menu-toggle" class="menu-hamburger">☰</button>
        </header>
        {{-- aqui se coloca todos los elmentos cambiantes --}}

        @yield('contenido')

    </main>

    <script src="{{asset('js/script.js')}}"></script>
</body>

</html>