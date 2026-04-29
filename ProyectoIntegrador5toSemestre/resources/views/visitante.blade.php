<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logoSISC.jpeg') }}" type="image/jpeg">
    <title>SISC — Seguridad Ciudadana Cali</title>

    {{-- Misma fuente que el portal admin --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- CSS visitante (paleta admin) --}}
    <link rel="stylesheet" href="{{ asset('css/sis-cali.css') }}">
</head>
<body>

<!-- ══════════════ NAV ══════════════ -->
<nav id="main-nav">

    {{-- Spacer para centrar la marca --}}
    <div class="nav-left-spacer"></div>

    {{-- Logo + nombre SISC centrado --}}
    <div class="nav-center">
        <div class="nav-brand">
            <img src="{{ asset('img/logoSISC.jpeg') }}" alt="SISC" class="nav-logo-img">
            <div class="nav-brand-text">
                <span class="nav-brand-name">SISC</span>
                <span class="nav-brand-sub">Sistema de Información de Seguridad Ciudadana</span>
            </div>
        </div>
    </div>

    {{-- Botón Administrador → menú de selección de Laravel --}}
    <div class="nav-right">
        <a href="{{ route('home') }}" class="btn-admin-link">
            🔐 Administrador
        </a>
    </div>

</nav>

<!-- ══════════════ CONTENIDO ══════════════ -->
<div id="page-inicio" class="page active">
    <div class="main-layout">

        {{-- Hero --}}
        <div class="hero-panel">
            <div>
                <div class="hero-title">Sistema de Seguridad Ciudadana</div>
                <div class="hero-sub">Cali, Valle del Cauca · Datos actualizados en tiempo real</div>
            </div>
            <div class="stat-pills">
                <div class="stat-pill"><span class="num">954</span><span class="lbl">Homicidios 2023</span></div>
                <div class="stat-pill"><span class="num">22</span><span class="lbl">Comunas</span></div>
                <div class="stat-pill"><span class="num">369</span><span class="lbl">Ag. Aguablanca</span></div>
                <div class="stat-pill"><span class="num">40</span><span class="lbl">Est. de Policía</span></div>
            </div>
        </div>

        {{-- Mapa + Chat --}}
        <div class="two-col">

            <!-- MAPA -->
            <div class="map-card">
                <div class="card-header">
                    <span class="card-title">🗺 Mapa Interactivo — Cali</span>
                    <div class="legend">
                        <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div>Riesgo Alto</div>
                        <div class="legend-item"><div class="legend-dot" style="background:#f97316"></div>Riesgo Medio</div>
                        <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div>Riesgo Bajo</div>
                        <div class="legend-item"><div class="legend-dot" style="background:#3b82f6"></div>Est. Policía</div>
                    </div>
                </div>
                <div id="mapa-cali"></div>
            </div>

            <!-- CHAT -->
            <div class="chat-card">
                <div class="card-header">
                    <span class="card-title">💬 Chat de Consultas</span>
                </div>
                <div class="chat-messages" id="chat-messages"></div>
                <div class="quick-questions" id="quick-questions">
                    <button class="quick-btn" onclick="sendQuick('¿Cuáles son los barrios más seguros de Cali?')">🟢 Barrios seguros</button>
                    <button class="quick-btn" onclick="sendQuick('¿Qué delitos son más frecuentes en Aguablanca?')">⚠️ Aguablanca</button>
                    <button class="quick-btn" onclick="sendQuick('¿Dónde puedo denunciar en Cali?')">📍 Dónde denunciar</button>
                    <button class="quick-btn" onclick="sendQuick('¿Cuáles son las comunas con mayor riesgo?')">🔴 Alto riesgo</button>
                    <button class="quick-btn" onclick="sendQuick('¿Qué pasó en Siloé en 2023?')">📊 Siloé</button>
                    <button class="quick-btn" onclick="sendQuick('Recomendaciones de seguridad')">🛡️ Recomendaciones</button>
                </div>
                <div class="chat-input-area">
                    <input type="text" id="chat-input" placeholder="Escribe tu consulta aquí..."
                           onkeydown="if(event.key==='Enter') sendChat()">
                    <button class="btn-send" onclick="sendChat()">➤</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/sis-cali.js') }}"></script>

<script>
  /**   
   * FIX MAPA NEGRO:
   * Inicializar el mapa directamente al cargar la página,
   * sin depender de la navegación entre secciones.
   */
  window.addEventListener('load', function () {
    var pagInicio = document.getElementById('page-inicio');
    if (pagInicio) {
      pagInicio.style.display = 'block';
      pagInicio.classList.add('active');
    }
    if (typeof initMap === 'function') {
      initMap('mapa-cali', null, function(m) { window.map1 = m; });
    }
    setTimeout(function () {
      if (window.map1 && typeof window.map1.invalidateSize === 'function') {
        window.map1.invalidateSize();
      }
    }, 300);
  });
</script>

</body>
</html>