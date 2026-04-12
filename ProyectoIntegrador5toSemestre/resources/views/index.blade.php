<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bienvenido - Tienda Admin</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body class="auth-screen">
        <main class="auth-page">
            <section class="auth-card">
                <div class="auth-hero">
                    <span class="eyebrow">Bienvenido al panel</span>
                    <h1>Accede al administrador oscuro</h1>
                    <p>Selecciona una opci�n para continuar: iniciar sesi�n como administrador o ingresar como visitante con un estilo moderno en azul y negro.</p>
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="auth-button primary">Ir al login</a>
                        <a href="{{ route('visitante') }}" class="auth-button secondary">Ingresar como visitante</a>
                    </div>
                </div>
                <div class="auth-panel">
                    <div class="auth-panel-card">
                        <span class="panel-label">Modo Dark</span>
                        <h2>Dise�o tecnol�gico</h2>
                        <p>Colores oscuros, bordes suaves y tipograf�a clara para una experiencia profesional y coherente con el panel administrativo.</p>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
