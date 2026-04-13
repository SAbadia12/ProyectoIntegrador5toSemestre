<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido - Administrador Web</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="auth-screen">
    <main class="auth-page">
        <section class="auth-card">
            <div class="auth-hero">
                <span class="eyebrow">Bienvenido a SISC</span>
                <h1>Seguridad a tu alcance</h1>
                <p>Selecciona una opción para continuar: iniciar sesión como administrador o ingresar como visitante.</p>
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="auth-button primary">Ir al login</a>
                    <a href="{{ route('visitante') }}" class="auth-button secondary">Ingresar como visitante</a>
                </div>
            </div>
            <div class="auth-panel">
                <div class="auth-panel-card">
                    <span class="panel-label">Tu seguridad comienza aquí</span>
                    <h2>Accede al conocimiento que protege y transforma la ciudad.</h2>
                    <p>Más que un sistema, este espacio es tu aliado en la vigilancia y la prevención. La seguridad comienza con datos claros y accesibles.</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>