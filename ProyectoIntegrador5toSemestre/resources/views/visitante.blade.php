<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Visitante - Tienda Admin</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body class="auth-screen">
        <main class="auth-page">
            <section class="login-card">
                <div class="login-header">
                    <span class="eyebrow">Zona visitante</span>
                    <h1>Acceso sin registro</h1>
                    <p>Explora la aplicación como visitante. Si quieres volver, usa los botones para regresar o iniciar sesión.</p>
                </div>
                <div class="auth-buttons">
                    <a href="{{ route('home') }}" class="auth-button secondary">Volver al inicio</a>
                    <a href="{{ route('login') }}" class="auth-button primary">Ir al login</a>
                </div>
            </section>
        </main>
    </body>
</html>
