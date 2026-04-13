<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/logoSISC.jpeg" type="image/jpeg">
        <title>SISC - Admin Web</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body class="auth-screen">
        <main class="auth-page">
            <section class="login-card">
                <div class="login-header">
                    <span class="eyebrow">Conexión segura</span>
                    <h1>Iniciar sesión</h1>
                    <p>Ingresa tus credenciales para acceder al panel administrativo.</p>
                </div>
                @if (session('error'))
                    <div class="login-error">{{ session('error') }}</div>
                @endif
                <form class="login-form" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" class="login-input" placeholder="correo@dominio.com" value="{{ old('email') }}" required>

                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" class="login-input" placeholder="••••••••" required>

                    <button type="submit" class="login-button">Entrar</button>
                </form>
                <div class="login-footer">
                    <a href="{{ route('home') }}">Volver al inicio</a>
                </div>
            </section>
        </main>
    </body>
</html>
