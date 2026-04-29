<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/logoSISC.jpeg') }}" type="image/jpeg">
    <title>SISC - Sistema de Información de Seguridad Ciudadana</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Misma fuente que el portal admin --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

    {{-- CSS del portal admin (variables, colores, botones) --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        /* ── variables del admin (heredadas de styles.css) ──────── */
        /* --bg-page:#050812  --bg-panel:#0b1725  --accent:#38bdf8  */

        .sisc-index-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            background: linear-gradient(180deg, #02060f 0%, #061225 100%);
        }

        /* ── Logo + título centrados arriba de la card ── */
        .sisc-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            margin-bottom: 36px;
        }

        .sisc-brand-logo {
            width: 96px;
            height: 96px;
            border-radius: 22px;
            border: 2px solid rgba(56, 191, 248, 0.35);
            box-shadow: 0 0 32px rgba(56, 191, 248, 0.18);
            object-fit: cover;
        }

        .sisc-brand-name {
            font-family: 'Roboto', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--accent, #38bdf8);
            letter-spacing: 4px;
            line-height: 1;
        }

        .sisc-brand-tagline {
            font-size: 0.78rem;
            font-weight: 400;
            color: var(--text-muted, #94a3b8);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-align: center;
        }

        /* ── Card principal ── */
        .sisc-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            width: min(900px, 100%);
            background: rgba(11, 23, 40, 0.96);
            border: 1px solid rgba(56, 114, 191, 0.22);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
        }

        /* ── Panel izquierdo (hero) ── */
        .sisc-hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 20px;
            padding: 48px 44px;
        }

        .sisc-eyebrow {
            display: inline-flex;
            align-items: center;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent, #38bdf8);
        }

        .sisc-hero h1 {
            font-size: clamp(1.9rem, 3vw, 2.8rem);
            font-weight: 700;
            color: var(--text, #e2e8f0);
            line-height: 1.1;
            margin: 0;
        }

        .sisc-hero p {
            color: var(--text-muted, #94a3b8);
            line-height: 1.75;
            font-size: 0.95rem;
            max-width: 340px;
        }

        .sisc-buttons {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 8px;
        }

        /* Botón primario — mismo estilo que el portal admin */
        .sisc-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border-radius: 999px;
            font-family: 'Roboto', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
            color: #07141f;
            border: none;
            cursor: pointer;
        }

        .sisc-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(56, 191, 248, 0.25);
            color: #07141f;
        }

        /* Botón secundario */
        .sisc-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border-radius: 999px;
            font-family: 'Roboto', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease;
            background: rgba(255, 255, 255, 0.04);
            color: var(--text, #e2e8f0);
            border: 1px solid rgba(96, 165, 250, 0.28);
        }

        .sisc-btn-secondary:hover {
            background: rgba(56, 114, 191, 0.16);
            transform: translateY(-2px);
            color: var(--text, #e2e8f0);
        }

        /* ── Panel derecho ── */
        .sisc-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 40px;
            background: linear-gradient(180deg, rgba(7, 26, 47, 0.95) 0%, rgba(11, 23, 40, 0.95) 100%);
            border-left: 1px solid rgba(56, 114, 191, 0.16);
        }

        .sisc-panel-inner {
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding: 28px;
            border-radius: 20px;
            background: rgba(15, 30, 55, 0.6);
            border: 1px solid rgba(56, 114, 191, 0.18);
        }

        .sisc-panel-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent, #38bdf8);
        }

        .sisc-panel h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text, #e2e8f0);
            line-height: 1.4;
            margin: 0;
        }

        .sisc-panel p {
            font-size: 0.88rem;
            color: var(--text-muted, #94a3b8);
            line-height: 1.75;
            margin: 0;
        }

        /* ── Divisor decorativo ── */
        .sisc-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(56, 191, 248, 0.3), transparent);
            margin: 8px 0;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .sisc-card {
                grid-template-columns: 1fr;
            }

            .sisc-panel {
                border-left: none;
                border-top: 1px solid rgba(56, 114, 191, 0.16);
            }

            .sisc-hero,
            .sisc-panel {
                padding: 36px 28px;
            }

            .sisc-brand-logo {
                width: 76px;
                height: 76px;
            }
        }
    </style>
</head>

<body class="auth-screen">
    <main class="sisc-index-page">

        {{-- Logo + nombre SISC centrado arriba --}}
        <div class="sisc-brand">
            <img src="{{ asset('img/logoSISC.jpeg') }}" alt="Logo SISC" class="sisc-brand-logo">
            <div class="sisc-brand-name">SISC</div>
            <div class="sisc-brand-tagline">Sistema de Información de Seguridad Ciudadana</div>
        </div>

        {{-- Card principal --}}
        <div class="sisc-card">

            {{-- Panel izquierdo --}}
            <div class="sisc-hero">
                <span class="sisc-eyebrow">Bienvenido a SISC</span>
                <h1>Seguridad a tu alcance</h1>
                <p>Selecciona una opción para continuar: iniciar sesión como administrador o ingresar como visitante.</p>

                <div class="sisc-buttons">
                    <a href="{{ route('login') }}" class="sisc-btn-primary">Ir al login</a>
                    <a href="{{ route('visitante') }}" class="sisc-btn-secondary">Ingresar como visitante</a>
                </div>
            </div>

            {{-- Panel derecho --}}
            <div class="sisc-panel">
                <div class="sisc-panel-inner">
                    <span class="sisc-panel-label">Tu seguridad comienza aquí</span>
                    <div class="sisc-divider"></div>
                    <h2>Accede al conocimiento que protege y transforma la ciudad.</h2>
                    <p>Más que un sistema, este espacio es tu aliado en la vigilancia y la prevención. La seguridad comienza con datos claros y accesibles.</p>
                </div>
            </div>

        </div>

    </main>
</body>

</html>