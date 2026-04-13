<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Tienda</title>
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
            <img src="{{asset('img/face3.png')}}" alt="avatar">
            <div class="profile-info">
               <span>{{ session('logged_user_name', 'logged_user_id') }} {{ session('logged_user_lastname') }}</span>
               <small>Bienvenido</small>
            </div>
         </div>
      </div>
      <nav class="slidebar-nav">
         <a href="#" class="element-slidebar-btn active">
            <img src="{{asset('img/compras.png')}}" alt="Dashboard">
            <span>Dashboard</span>
         </a>
         <a href="#" class="element-slidebar-btn">
            <img src="{{asset('img/category.png')}}" alt="NivelRiesgo">
            <span><a href="{{route('nivel.index')}}"> Niveles de riesgo</span>
         </a>
         <a href="#" class="element-slidebar-btn">
            <img src="{{asset('img/rokrt.png')}}" alt="PuntoCardinal">
            <span><a href="{{route('cardinal.index')}}">Puntos Cardinales</span>
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