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

    {{-- Botones: Comentario (RF5) + Emergencias (RF22) + Administrador --}}
    <div class="nav-right" style="display:flex; gap:10px; align-items:center;">
        <button type="button" onclick="abrirModalComentario()" class="btn-admin-link" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);color:#07141f;border:none;cursor:pointer;font-weight:700;">
            💬 Dejar comentario
        </button>
        <a href="{{ route('emergencias') }}" class="btn-admin-link" style="background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;">
            🚨 Emergencias
        </a>
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
                <div class="stat-pill"><span class="num">{{ $stats['homicidios'] ?? 0 }}</span><span class="lbl">Homicidios último año</span></div>
                <div class="stat-pill"><span class="num">{{ $stats['comunas'] ?? 0 }}</span><span class="lbl">Comunas</span></div>
                <div class="stat-pill"><span class="num">{{ $stats['aguablanca'] ?? 0 }}</span><span class="lbl">Ag. Aguablanca</span></div>
                <div class="stat-pill"><span class="num">{{ $stats['estaciones'] ?? 0 }}</span><span class="lbl">Est. de Policía</span></div>
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

<!-- ════════════ MODAL COMENTARIOS (RF5) ════════════ -->
<div id="modal-comentario" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#0b1725;border:1px solid rgba(56,114,191,.3);border-radius:20px;max-width:520px;width:100%;padding:32px;box-shadow:0 24px 64px rgba(0,0,0,.5);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 style="color:#38bdf8;margin:0;font-size:1.4rem;">💬 Deja tu comentario</h2>
            <button type="button" onclick="cerrarModalComentario()" style="background:none;border:none;color:#94a3b8;font-size:1.6rem;cursor:pointer;">&times;</button>
        </div>
        <p style="color:#94a3b8;font-size:.9rem;margin-top:0;">Tu opinión nos ayuda a mejorar la plataforma. Será revisado por nuestro equipo antes de publicarse.</p>

        <form id="form-comentario" onsubmit="enviarComentario(event)">
            <div style="margin-bottom:14px;">
                <label style="color:#e2e8f0;font-size:.85rem;font-weight:600;display:block;margin-bottom:6px;">Nombre *</label>
                <input type="text" name="nombre" required maxlength="100" placeholder="Tu nombre"
                       style="width:100%;background:rgba(7,18,35,.8);border:1px solid rgba(56,114,191,.3);color:#e2e8f0;padding:10px 14px;border-radius:10px;font-family:inherit;">
            </div>

            <div style="margin-bottom:14px;">
                <label style="color:#e2e8f0;font-size:.85rem;font-weight:600;display:block;margin-bottom:6px;">Email (opcional)</label>
                <input type="email" name="email" maxlength="150" placeholder="tu@email.com"
                       style="width:100%;background:rgba(7,18,35,.8);border:1px solid rgba(56,114,191,.3);color:#e2e8f0;padding:10px 14px;border-radius:10px;font-family:inherit;">
            </div>

            <div style="margin-bottom:18px;">
                <label style="color:#e2e8f0;font-size:.85rem;font-weight:600;display:block;margin-bottom:6px;">Comentario *</label>
                <textarea name="contenido" required minlength="5" maxlength="1000" rows="4" placeholder="Cuéntanos tu experiencia, sugerencia o reporte..."
                          style="width:100%;background:rgba(7,18,35,.8);border:1px solid rgba(56,114,191,.3);color:#e2e8f0;padding:10px 14px;border-radius:10px;font-family:inherit;resize:vertical;"></textarea>
            </div>

            <div id="comentario-feedback" style="display:none;padding:10px 14px;border-radius:10px;margin-bottom:14px;font-size:.9rem;"></div>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="cerrarModalComentario()"
                        style="background:rgba(255,255,255,.06);color:#e2e8f0;border:1px solid rgba(96,165,250,.28);padding:10px 22px;border-radius:999px;cursor:pointer;font-weight:600;">
                    Cancelar
                </button>
                <button type="submit" id="btn-enviar-comentario"
                        style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);color:#07141f;border:none;padding:10px 26px;border-radius:999px;cursor:pointer;font-weight:700;">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/sis-cali.js') }}"></script>

<script>
  // ════════════════════════════════════════════════════════════
  // MAPA DE CALOR - DATA FROM LARAVEL
  // ════════════════════════════════════════════════════════════
  // Datos pasados directamente desde el controller de la ruta /visitante.
  // Cada ubicación trae lat/lng y el color de su nivel de riesgo.
  // ════════════════════════════════════════════════════════════
  const UBICACIONES_DATA = @json($ubicaciones);
  const ESTACIONES_DATA  = @json($estacionesPolicia);

  /**
   * Renderiza el mapa de calor de Cali con todas las ubicaciones,
   * coloreadas según su nivel de riesgo.
   */
  function renderMapaCali() {
    const map = L.map('mapa-cali', { zoomControl: true })
      .setView([3.4516, -76.5320], 12);  // Centro de Cali

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; OpenStreetMap &copy; CARTO',
      maxZoom: 18
    }).addTo(map);

    // ── Ubicaciones (mapa de calor por nivel de riesgo) ─────
    UBICACIONES_DATA.forEach(u => {
      if (!u.lat || !u.lng) return;

      // Círculo coloreado según el nivel de riesgo de la ubicación
      L.circleMarker([u.lat, u.lng], {
        radius:       14,
        fillColor:    u.nivel_color,
        color:        u.nivel_color,
        weight:       2,
        opacity:      0.9,
        fillOpacity:  0.45
      }).bindPopup(`
        <div style="font-family:Roboto,sans-serif;min-width:200px;color:#1f2937">
          <strong style="font-size:1rem">📍 ${u.barrio}</strong><br>
          <span style="color:#6b7280;font-size:.8rem">Zona: </span>
          <span style="font-size:.85rem">${u.zona_nombre}</span><br>
          <span style="color:#6b7280;font-size:.8rem">Dirección: </span>
          <strong style="font-size:.85rem">${u.direccion}</strong><br>
          <span style="color:#6b7280;font-size:.8rem">Punto cardinal: </span>
          <span style="font-size:.85rem">${u.punto_cardinal}</span><br>
          <span style="color:#6b7280;font-size:.8rem">Nivel de riesgo: </span>
          <span style="background:${u.nivel_color};color:#fff;padding:2px 10px;border-radius:999px;font-size:.75rem;font-weight:700;text-transform:uppercase">
            ${u.nivel_nombre}
          </span>
        </div>
      `).addTo(map);
    });

    // ── Estaciones de policía ──────────────────────────────
    const polIcon = L.divIcon({
      html: '<div style="background:#3b82f6;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;border:2px solid #93c5fd;box-shadow:0 2px 6px rgba(0,0,0,.3)">🚔</div>',
      iconSize:   [24, 24],
      iconAnchor: [12, 12],
      className:  ''
    });

    ESTACIONES_DATA.forEach(e => {
      if (!e.lat || !e.lng) return;

      L.marker([e.lat, e.lng], { icon: polIcon })
        .bindPopup(`
          <div style="font-family:Roboto,sans-serif;min-width:200px;color:#1f2937">
            <strong>🚔 ${e.nombre}</strong><br>
            <span style="color:#6b7280;font-size:.8rem">📍 ${e.direccion || 'Sin dirección'}</span><br>
            <span style="color:#6b7280;font-size:.8rem">📞 ${e.telefono || 'Sin teléfono'}</span>
          </div>
        `).addTo(map);
    });

    // Recalcular tamaño después de un breve delay
    setTimeout(() => map.invalidateSize(), 300);

    return map;
  }

  // ════════════════════════════════════════════════════════════
  // CHAT - RESPUESTAS DESDE LA BD (override de sis-cali.js)
  // ════════════════════════════════════════════════════════════
  const CHAT_BD = @json($chatData);

  // Construye respuestas formateadas con datos reales
  function buildChatRespuestas() {
    const r = {};

    // Barrios seguros
    if (CHAT_BD.barrios_seguros && CHAT_BD.barrios_seguros.length) {
      r.seguros = `🟢 <strong>Zonas con riesgo BAJO en Cali:</strong><br>` +
        CHAT_BD.barrios_seguros.map(b => `• ${b}`).join('<br>') +
        `<br><br>Estas zonas registran las tasas más bajas de delitos según nuestra base de datos.`;
    } else {
      r.seguros = `🟢 No hay zonas registradas con nivel de riesgo bajo aún.`;
    }

    // Aguablanca
    if (CHAT_BD.aguablanca_top && CHAT_BD.aguablanca_top.length) {
      const top = CHAT_BD.aguablanca_top.map(d => `• ${d.tipo}: <strong>${d.total}</strong> casos`).join('<br>');
      r.aguablanca = `⚠️ <strong>Distrito de Aguablanca</strong> (Comunas 13, 14, 15, 21):<br>` +
        `Total: <strong>${CHAT_BD.aguablanca_total}</strong> delitos registrados.<br><br>` +
        `Tipos más frecuentes:<br>${top}`;
    } else {
      r.aguablanca = `⚠️ No hay datos de delitos en Aguablanca.`;
    }

    // Dónde denunciar
    if (CHAT_BD.denunciar && CHAT_BD.denunciar.length) {
      const lista = CHAT_BD.denunciar.map(e =>
        `• <strong>${e.nombre}</strong>${e.direccion ? '<br>&nbsp;&nbsp;📍 ' + e.direccion : ''}${e.telefono ? '<br>&nbsp;&nbsp;📞 ' + e.telefono : ''}`
      ).join('<br>');
      r.denunciar = `📍 <strong>Lugares de denuncia más cercanos:</strong><br>${lista}<br><br>Emergencias: <strong>📞 123</strong>`;
    } else {
      r.denunciar = `📍 No hay estaciones de policía registradas.`;
    }

    // Alto riesgo
    if (CHAT_BD.alto_riesgo && CHAT_BD.alto_riesgo.length) {
      r.comunas = `🔴 <strong>Zonas con riesgo ALTO en Cali:</strong><br>` +
        CHAT_BD.alto_riesgo.map(z => `• ${z}`).join('<br>') +
        `<br><br>Recomendamos extremar precauciones en estas zonas.`;
    } else {
      r.comunas = `🔴 No hay zonas registradas con riesgo alto.`;
    }

    // Siloé
    if (CHAT_BD.siloe_top && CHAT_BD.siloe_top.length) {
      const top = CHAT_BD.siloe_top.map(d => `• ${d.tipo}: <strong>${d.total}</strong> casos`).join('<br>');
      r.siloe = `📊 <strong>Siloé (Comuna 20):</strong><br>` +
        `Total: <strong>${CHAT_BD.siloe_total}</strong> delitos registrados<br><br>` +
        `Tipos principales:<br>${top}`;
    } else {
      r.siloe = `📊 No hay datos suficientes de Siloé.`;
    }

    // Recomendaciones (estática)
    r.recomendaciones = `🛡️ <strong>Recomendaciones de seguridad:</strong><br>` +
      `• Evita zonas de riesgo alto en horarios nocturnos<br>` +
      `• Reporta cualquier emergencia al <strong>📞 123</strong><br>` +
      `• Comparte tu ubicación con familiares al desplazarte<br>` +
      `• No exhibas objetos de valor en la calle<br>` +
      `• Consulta el mapa antes de visitar una zona desconocida`;

    r.default = `No tengo datos específicos sobre eso. Consulta el mapa interactivo, los botones de consulta rápida, o llama al <strong>📞 123</strong> para emergencias.`;

    return r;
  }

  const CHAT_RESPUESTAS_BD = buildChatRespuestas();

  // Override de getResponse de sis-cali.js para usar nuestros datos
  window.getResponse = async function (q) {
    const ql = q.toLowerCase();
    if (ql.includes('segur') || ql.includes('tranquil'))             return CHAT_RESPUESTAS_BD.seguros;
    if (ql.includes('aguablanca') || ql.includes('mojica'))          return CHAT_RESPUESTAS_BD.aguablanca;
    if (ql.includes('denunci') || ql.includes('policia') || ql.includes('policía')) return CHAT_RESPUESTAS_BD.denunciar;
    if (ql.includes('riesgo') || ql.includes('peligros'))            return CHAT_RESPUESTAS_BD.comunas;
    if (ql.includes('siloé') || ql.includes('siloe'))                return CHAT_RESPUESTAS_BD.siloe;
    if (ql.includes('recomenda') || ql.includes('consejo'))          return CHAT_RESPUESTAS_BD.recomendaciones;
    return CHAT_RESPUESTAS_BD.default;
  };

  // ════════════════════════════════════════════════════════════
  // MODAL COMENTARIOS (RF5)
  // ════════════════════════════════════════════════════════════
  function abrirModalComentario() {
    const m = document.getElementById('modal-comentario');
    m.style.display = 'flex';
  }
  function cerrarModalComentario() {
    document.getElementById('modal-comentario').style.display = 'none';
    document.getElementById('form-comentario').reset();
    document.getElementById('comentario-feedback').style.display = 'none';
  }

  async function enviarComentario(ev) {
    ev.preventDefault();
    const form = ev.target;
    const fb   = document.getElementById('comentario-feedback');
    const btn  = document.getElementById('btn-enviar-comentario');

    btn.disabled = true;
    btn.textContent = 'Enviando...';
    fb.style.display = 'none';

    const data = {
      nombre:    form.nombre.value.trim(),
      email:     form.email.value.trim() || null,
      contenido: form.contenido.value.trim(),
    };

    try {
      const res = await fetch('{{ route('comentario.store') }}', {
        method: 'POST',
        headers: {
          'Content-Type':     'application/json',
          'Accept':           'application/json',
          'X-CSRF-TOKEN':     '{{ csrf_token() }}',
        },
        body: JSON.stringify(data),
      });
      const json = await res.json();

      if (res.ok && json.success) {
        fb.style.background = 'rgba(34,197,94,.15)';
        fb.style.color      = '#86efac';
        fb.style.border     = '1px solid rgba(34,197,94,.3)';
        fb.textContent      = '✅ ' + json.message;
        fb.style.display    = 'block';
        form.reset();
        setTimeout(cerrarModalComentario, 2000);
      } else {
        const msg = json.message || (json.errors ? Object.values(json.errors).flat().join(' • ') : 'Error al enviar');
        fb.style.background = 'rgba(239,68,68,.15)';
        fb.style.color      = '#fca5a5';
        fb.style.border     = '1px solid rgba(239,68,68,.3)';
        fb.textContent      = '⚠️ ' + msg;
        fb.style.display    = 'block';
      }
    } catch (err) {
      fb.style.background = 'rgba(239,68,68,.15)';
      fb.style.color      = '#fca5a5';
      fb.style.border     = '1px solid rgba(239,68,68,.3)';
      fb.textContent      = '⚠️ Error de conexión: ' + err.message;
      fb.style.display    = 'block';
    } finally {
      btn.disabled = false;
      btn.textContent = 'Enviar';
    }
  }

  // Cerrar modal con Escape o click fuera
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModalComentario();
  });
  document.addEventListener('click', e => {
    if (e.target.id === 'modal-comentario') cerrarModalComentario();
  });

  // ════════════════════════════════════════════════════════════
  // INIT
  // ════════════════════════════════════════════════════════════
  window.addEventListener('load', function () {
    const pagInicio = document.getElementById('page-inicio');
    if (pagInicio) {
      pagInicio.style.display = 'block';
      pagInicio.classList.add('active');
    }

    // Inicializar el mapa
    try {
      window.map1 = renderMapaCali();
    } catch (err) {
      console.error('Error renderizando mapa:', err);
    }
  });
</script>

</body>
</html>