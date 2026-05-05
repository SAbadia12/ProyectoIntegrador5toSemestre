<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logoSISC.jpeg') }}" type="image/jpeg">
    <title>SISC — Líneas de Emergencia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Reusa el mismo CSS del visitante --}}
    <link rel="stylesheet" href="{{ asset('css/sis-cali.css') }}">

    <style>
        body { background: linear-gradient(180deg, #02060f 0%, #061225 100%); min-height: 100vh; margin: 0; font-family: 'Roboto', sans-serif; color: #e2e8f0; }
        .emerg-container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }
        .emerg-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
        .emerg-title { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 700; color: #38bdf8; margin: 0; }
        .emerg-sub { color: #94a3b8; font-size: 0.95rem; margin-top: 4px; }
        .btn-back { background: rgba(255,255,255,0.06); color: #e2e8f0; padding: 12px 24px; border-radius: 999px; text-decoration: none; font-weight: 600; border: 1px solid rgba(96,165,250,0.28); transition: all .2s; }
        .btn-back:hover { background: rgba(56,114,191,0.16); transform: translateY(-2px); }

        .emerg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
        .emerg-card { background: rgba(11,23,40,0.96); border: 1px solid rgba(56,114,191,0.22); border-radius: 20px; padding: 24px; transition: transform .2s, box-shadow .2s; position: relative; overflow: hidden; }
        .emerg-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--card-color, #38bdf8); }
        .emerg-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(0,0,0,0.4); }
        .emerg-icon { font-size: 2rem; margin-bottom: 12px; display: block; }
        .emerg-name { font-size: 1.05rem; font-weight: 700; color: #e2e8f0; margin: 0 0 6px; }
        .emerg-desc { font-size: 0.85rem; color: #94a3b8; line-height: 1.5; margin: 0 0 16px; }
        .emerg-number { display: block; font-size: 2.4rem; font-weight: 700; color: var(--card-color, #38bdf8); letter-spacing: 2px; }
        .emerg-call { display: inline-block; margin-top: 12px; background: linear-gradient(135deg, #0ea5e9, #38bdf8); color: #07141f; padding: 10px 18px; border-radius: 999px; text-decoration: none; font-weight: 700; font-size: 0.85rem; }
        .emerg-call:hover { transform: translateY(-1px); }

        .emerg-warning { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.3); border-radius: 16px; padding: 20px; margin-bottom: 24px; color: #fca5a5; font-size: 0.9rem; line-height: 1.6; }
        .emerg-warning strong { color: #ef4444; }
    </style>
</head>
<body>

<div class="emerg-container">

    <header class="emerg-header">
        <div>
            <h1 class="emerg-title">📞 Líneas de Emergencia</h1>
            <p class="emerg-sub">Cali, Valle del Cauca · Números nacionales y locales</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn-back">← Volver</a>
    </header>

    <div class="emerg-warning">
        <strong>⚠️ En caso de emergencia, mantén la calma.</strong>
        Identifica claramente tu ubicación, tipo de emergencia y número de personas afectadas antes de llamar.
        Estas líneas son gratuitas desde cualquier teléfono fijo o celular.
    </div>

    <div class="emerg-grid">

        <div class="emerg-card" style="--card-color: #ef4444;">
            <span class="emerg-icon">🚨</span>
            <h2 class="emerg-name">Emergencia Nacional</h2>
            <p class="emerg-desc">Línea única de atención de emergencias en Colombia.</p>
            <span class="emerg-number">123</span>
            <a href="tel:123" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #3b82f6;">
            <span class="emerg-icon">👮</span>
            <h2 class="emerg-name">Policía Nacional</h2>
            <p class="emerg-desc">Reportar delitos, hurtos, riñas y emergencias de seguridad.</p>
            <span class="emerg-number">123</span>
            <a href="tel:123" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #f97316;">
            <span class="emerg-icon">🚒</span>
            <h2 class="emerg-name">Bomberos Cali</h2>
            <p class="emerg-desc">Incendios, rescates, fugas y emergencias estructurales.</p>
            <span class="emerg-number">119</span>
            <a href="tel:119" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #22c55e;">
            <span class="emerg-icon">🚑</span>
            <h2 class="emerg-name">Cruz Roja</h2>
            <p class="emerg-desc">Atención prehospitalaria y traslados médicos de urgencia.</p>
            <span class="emerg-number">132</span>
            <a href="tel:132" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #a855f7;">
            <span class="emerg-icon">🏥</span>
            <h2 class="emerg-name">Defensa Civil</h2>
            <p class="emerg-desc">Atención de desastres naturales y emergencias comunitarias.</p>
            <span class="emerg-number">144</span>
            <a href="tel:144" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #ec4899;">
            <span class="emerg-icon">👨‍👩‍👧</span>
            <h2 class="emerg-name">Bienestar Familiar</h2>
            <p class="emerg-desc">Protección a niños, niñas y adolescentes (ICBF).</p>
            <span class="emerg-number">141</span>
            <a href="tel:141" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #14b8a6;">
            <span class="emerg-icon">🧑‍⚖️</span>
            <h2 class="emerg-name">Fiscalía (CAIVAS / CAVIF)</h2>
            <p class="emerg-desc">Denuncia de violencia sexual e intrafamiliar.</p>
            <span class="emerg-number">122</span>
            <a href="tel:122" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #f59e0b;">
            <span class="emerg-icon">🔍</span>
            <h2 class="emerg-name">Línea Anti-secuestro</h2>
            <p class="emerg-desc">GAULA — Reporte de secuestros y extorsiones.</p>
            <span class="emerg-number">165</span>
            <a href="tel:165" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #06b6d4;">
            <span class="emerg-icon">📢</span>
            <h2 class="emerg-name">Línea Mujer (Cali)</h2>
            <p class="emerg-desc">Atención a mujeres víctimas de violencia.</p>
            <span class="emerg-number">155</span>
            <a href="tel:155" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #8b5cf6;">
            <span class="emerg-icon">🛡️</span>
            <h2 class="emerg-name">CTI — Fiscalía</h2>
            <p class="emerg-desc">Cuerpo Técnico de Investigación.</p>
            <span class="emerg-number">157</span>
            <a href="tel:157" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #84cc16;">
            <span class="emerg-icon">🚦</span>
            <h2 class="emerg-name">Tránsito Cali</h2>
            <p class="emerg-desc">Accidentes de tránsito y movilidad.</p>
            <span class="emerg-number">127</span>
            <a href="tel:127" class="emerg-call">Llamar ahora</a>
        </div>

        <div class="emerg-card" style="--card-color: #6366f1;">
            <span class="emerg-icon">⚡</span>
            <h2 class="emerg-name">EMCALI Emergencias</h2>
            <p class="emerg-desc">Energía, acueducto, alcantarillado y telecomunicaciones.</p>
            <span class="emerg-number">177</span>
            <a href="tel:177" class="emerg-call">Llamar ahora</a>
        </div>

    </div>
</div>

</body>
</html>


