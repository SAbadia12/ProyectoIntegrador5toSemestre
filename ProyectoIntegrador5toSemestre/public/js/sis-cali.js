// ═══════════════════════════════════════════
// STATE — solo variables de control, sin datos
// ═══════════════════════════════════════════
let currentUser = null;
let currentPage = 'inicio';
let currentAdminTab = 'tipos';
let deleteTarget = null;
let editTarget = null;
let map1 = null, map2 = null;

// ═══════════════════════════════════════════
// NAVIGATION
// ═══════════════════════════════════════════
function goPage(p) {
  document.querySelectorAll('.page').forEach(el => el.classList.remove('active'));
  document.getElementById('page-' + p).classList.add('active');
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
  const idx = ['inicio','mapa','estadisticas','denuncia','admin'].indexOf(p);
  const btns = document.querySelectorAll('.nav-btn');
  if(idx >= 0 && btns[idx]) btns[idx].classList.add('active');
  currentPage = p;
  if(p === 'inicio') initMap('mapa-cali', map1, m => map1 = m);
  if(p === 'mapa')   { initMap('mapa-cali-2', map2, m => map2 = m); renderComunas(); }
  if(p === 'estadisticas') renderStats();
  if(p === 'admin')  adminTab(currentAdminTab);
}

// ═══════════════════════════════════════════
// AUTH — solo administradores necesitan login
// ═══════════════════════════════════════════
function openAdminLogin() {
  document.getElementById('login-user').value = '';
  document.getElementById('login-pass').value = '';
  document.getElementById('login-error').classList.remove('show');
  document.getElementById('modal-admin-login').classList.add('open');
}

async function doLogin() {
  const username = document.getElementById('login-user').value.trim();
  const password = document.getElementById('login-pass').value;

  try {
    // Llama a la API real en lugar de buscar en el array local
    const user = await AuthService.login(username, password);

    currentUser = user;
    document.getElementById('nav-user-name').textContent = user.username;
    document.getElementById('nav-role-badge').textContent = user.rol;
    document.getElementById('nav-guest-area').style.display = 'none';
    document.getElementById('nav-admin-area').style.display = 'flex';
    document.getElementById('btn-admin-nav').style.display = '';
    document.getElementById('login-error').classList.remove('show');
    closeModal('modal-admin-login');
    goPage('admin');
    showToast('¡Bienvenido, ' + user.username + '!', 'success');

  } catch (err) {
    document.getElementById('login-error').classList.add('show');
  }
}

function logout() {
  AuthService.logout();
  currentUser = null;
  document.getElementById('nav-guest-area').style.display = 'flex';
  document.getElementById('nav-admin-area').style.display = 'none';
  document.getElementById('btn-admin-nav').style.display = 'none';
  goPage('inicio');
  showToast('Sesión cerrada', 'success');
}

// ═══════════════════════════════════════════
// MAP
// ═══════════════════════════════════════════
function getColor(riesgo) {
  if (!riesgo) return '#22c55e';
  const r = riesgo.toLowerCase();
  return r === 'alto' || r === 'crítico' ? '#ef4444'
       : r === 'medio' ? '#f97316'
       : '#22c55e';
}

async function initMap(divId, mapVar, setter) {
  if (mapVar) { mapVar.invalidateSize(); return; }

  const m = L.map(divId, { zoomControl: true }).setView([3.4200, -76.5200], 12);
  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CARTO',
    maxZoom: 18
  }).addTo(m);

  setter(m); // guardar referencia ya para no duplicar

  try {
    // ── Zonas de riesgo desde la API ──────────────────
    const ubicaciones = await UbicacionesService.getAll();

    ubicaciones.forEach(u => {
      // Solo las que tienen coordenadas definidas en la BD
      if (!u.lat || !u.lng) return;
      const color = getColor(u.nivel_riesgo || u.riesgo);
      const hom   = u.homicidios || 0;

      L.circleMarker([u.lat, u.lng], {
        radius: 12 + Math.min(hom / 8, 14),
        fillColor: color, color, weight: 1,
        opacity: 0.8, fillOpacity: 0.35
      }).bindPopup(`
        <div style="font-family:Inter,sans-serif;min-width:180px">
          <strong style="font-size:1rem">${u.nombre}</strong><br>
          <span style="color:#9ca3af;font-size:0.8rem">Comuna: </span><strong>${u.comuna}</strong><br>
          <span style="color:#9ca3af;font-size:0.8rem">Nivel de riesgo: </span>
          <span style="color:${color};font-weight:600;text-transform:uppercase;font-size:0.8rem">${u.nivel_riesgo || '—'}</span>
        </div>
      `).addTo(m);
    });

    // ── Lugares de denuncia / policía desde la API ────
    const lugares = await LugaresService.getAll();
    const polIcon = L.divIcon({
      html: '<div style="background:#3b82f6;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;border:2px solid #93c5fd">🚔</div>',
      iconSize: [20,20], iconAnchor: [10,10], className: ''
    });

    lugares.forEach(l => {
      if (!l.lat || !l.lng) return;
      L.marker([l.lat, l.lng], { icon: polIcon })
        .bindPopup(`
          <div style="font-family:Inter,sans-serif;min-width:180px">
            <strong>🚔 ${l.nombre}</strong><br>
            <span style="color:#9ca3af;font-size:0.8rem">📍 ${l.direccion || ''}</span><br>
            <span style="color:#9ca3af;font-size:0.8rem">📞 ${l.telefono || ''}</span>
          </div>
        `).addTo(m);
    });

  } catch (err) {
    console.warn('No se pudieron cargar datos del mapa desde la API:', err.message);
  }
}

// ═══════════════════════════════════════════
// COMUNAS GRID
// ═══════════════════════════════════════════
async function renderComunas() {
  const g = document.getElementById('comunas-grid');
  g.innerHTML = '<p style="color:#666;padding:1rem">Cargando comunas…</p>';

  try {
    const data = await ConsultasService.resumenComunas();

    if (!data.length) {
      g.innerHTML = '<p style="color:#666;padding:1rem">Sin datos disponibles.</p>';
      return;
    }

    g.innerHTML = data.map(c => `
      <div class="comuna-card">
        <div>
          <div class="comuna-name">Cali — Comuna ${c.comuna || '—'}</div>
          <div class="comuna-num">${c.nombre || ''}</div>
        </div>
        <div>
          <div class="risk-badge risk-${(c.riesgo || 'bajo').toLowerCase()}">${c.riesgo || 'N/A'}</div>
          <div style="font-size:0.7rem;color:#666;text-align:center;margin-top:3px">${c.total_denuncias} den.</div>
        </div>
      </div>
    `).join('');

  } catch (err) {
    g.innerHTML = '<p style="color:#f87171;padding:1rem">Error cargando comunas. Verifica que el servidor esté activo.</p>';
  }
}

// ═══════════════════════════════════════════
// STATS
// ═══════════════════════════════════════════
async function renderStats() {
  // ── Gráfica de delitos frecuentes desde la API ────
  try {
    const delitos = await DenunciasService.getDelitosTop();
    const maxD = delitos[0]?.total || 1;

    document.getElementById('bar-delitos').innerHTML = delitos.map(d => `
      <div class="bar-row">
        <div class="bar-label">${d.tipo_delito}</div>
        <div class="bar-fill-bg">
          <div class="bar-fill" style="width:${(d.total/maxD)*100}%;background:#f97316"></div>
        </div>
        <div class="bar-val">${d.total}</div>
      </div>
    `).join('');
  } catch (err) {
    document.getElementById('bar-delitos').innerHTML = '<p style="color:#666">Sin datos</p>';
  }

  // ── Gráfica de denuncias por comuna desde la API ──
  try {
    const comunas = await ConsultasService.resumenComunas();
    const top = [...comunas].sort((a,b) => b.total_denuncias - a.total_denuncias).slice(0, 8);
    const max = top[0]?.total_denuncias || 1;

    document.getElementById('bar-chart').innerHTML = top.map(c => `
      <div class="bar-row">
        <div class="bar-label">C.${c.comuna} ${(c.nombre||'').slice(0,8)}</div>
        <div class="bar-fill-bg">
          <div class="bar-fill" style="width:${(c.total_denuncias/max)*100}%;background:${getColor(c.riesgo)}"></div>
        </div>
        <div class="bar-val">${c.total_denuncias}</div>
      </div>
    `).join('');
  } catch (err) {
    document.getElementById('bar-chart').innerHTML = '<p style="color:#666">Sin datos</p>';
  }
}

// ═══════════════════════════════════════════
// CHAT
// ═══════════════════════════════════════════
const CHAT_RESPONSES = {
  'seguros':        `🟢 <strong>Barrios más seguros de Cali (riesgo bajo):</strong><br>• Ciudad Jardín (C. 19)<br>• El Ingenio (C. 17)<br>• Santa Mónica (C. 2)<br>• Meléndez (C. 18)<br><br>Registran las tasas de homicidios más bajas.`,
  'aguablanca':     `⚠️ <strong>Distrito de Aguablanca — Datos 2023:</strong><br>• Concentra el <strong>39%</strong> de los homicidios de Cali<br>• 369 de 954 homicidios totales<br>• Barrios más afectados: Mojica (29), Comuneros I (29), Morichal (24)`,
  'denunciar':      `📍 <strong>Lugares para denunciar en Cali:</strong><br>• SIJIN Cali Centro – Cra 3 #12-30 – 📞 602-4456789<br>• CAI Aguablanca – Cll 72 #46-10<br>• Fiscalía General – Cll 11 #4-22<br><br>Emergencias: <strong>📞 123</strong>`,
  'comunas':        `🔴 <strong>Comunas con mayor riesgo:</strong><br>• C. 15 (Mojica): 113 hom. — ALTO<br>• C. 13: 100 hom. — ALTO<br>• C. 9: 70 hom. — ALTO<br>• C. 20 (Siloé): 72 hom. — ALTO`,
  'siloe':          `📊 <strong>Siloé (C. 20):</strong><br>• 2023: +400% homicidios vs 2022<br>• Tasa: 72.6 por 100.000 hab.<br>• Causa principal: pandillas y microtráfico`,
  'recomendaciones':`🛡️ <strong>Recomendaciones:</strong><br>• Evita zonas de riesgo alto de noche<br>• Reporta al 📞 123<br>• Comparte tu ubicación con familiares<br>• No exhibas objetos de valor`,
  'default':        `No tengo datos específicos sobre eso. Consulta el mapa interactivo o llama al <strong>📞 123</strong> para emergencias.`
};

// Respuesta dinámica: primero busca en la API, si no usa respuestas estáticas
async function getResponse(q) {
  const ql = q.toLowerCase();

  // Intentar respuesta dinámica desde la API para comunas
  if (ql.includes('comuna') && /\d+/.test(ql)) {
    const num = ql.match(/\d+/)[0];
    try {
      const data = await DenunciasService.getByComuna(num);
      if (data.length) {
        return `📋 <strong>Comuna ${num} — ${data.length} denuncia(s) registrada(s):</strong><br>` +
          data.slice(0,3).map(d => `• ${d.fecha} — ${d.descripcion}`).join('<br>');
      }
    } catch {}
  }

  // Respuestas estáticas por palabra clave
  if (ql.includes('segur') || ql.includes('tranquil'))        return CHAT_RESPONSES['seguros'];
  if (ql.includes('aguablanca') || ql.includes('mojica'))     return CHAT_RESPONSES['aguablanca'];
  if (ql.includes('denunci') || ql.includes('policia') || ql.includes('policía')) return CHAT_RESPONSES['denunciar'];
  if (ql.includes('riesgo') || ql.includes('peligros'))       return CHAT_RESPONSES['comunas'];
  if (ql.includes('siloé') || ql.includes('siloe'))           return CHAT_RESPONSES['siloe'];
  if (ql.includes('recomenda') || ql.includes('consejo'))     return CHAT_RESPONSES['recomendaciones'];
  return CHAT_RESPONSES['default'];
}

function initChatWelcome() {
  const area = document.getElementById('chat-messages');
  area.innerHTML = '';
  addMsg('bot', `¡Hola! 👋 Soy el asistente de <strong>CaliSegura</strong>. Puedo informarte sobre:<br>• Niveles de riesgo por zona<br>• Estadísticas de delitos<br>• Lugares de denuncia en Cali<br>• Recomendaciones de seguridad<br><br>¿En qué te puedo ayudar hoy?`);
}

function addMsg(type, html) {
  const area = document.getElementById('chat-messages');
  const now  = new Date().toLocaleTimeString('es-CO', { hour:'2-digit', minute:'2-digit' });
  const div  = document.createElement('div');
  div.className = 'msg ' + type;
  div.innerHTML = `<div class="msg-bubble">${html}</div><div class="msg-time">${now}</div>`;
  area.appendChild(div);
  area.scrollTop = area.scrollHeight;
}

function addTyping() {
  const area = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'msg bot';
  div.id = 'typing-msg';
  div.innerHTML = `<div class="msg-bubble"><div class="typing"><span></span><span></span><span></span></div></div>`;
  area.appendChild(div);
  area.scrollTop = area.scrollHeight;
}

function removeTyping() {
  const t = document.getElementById('typing-msg');
  if (t) t.remove();
}

async function sendChat() {
  const inp = document.getElementById('chat-input');
  const q   = inp.value.trim();
  if (!q) return;
  addMsg('user', q);
  inp.value = '';
  addTyping();
  const resp = await getResponse(q);
  setTimeout(() => { removeTyping(); addMsg('bot', resp); }, 600);
}

function sendQuick(q) {
  document.getElementById('chat-input').value = q;
  sendChat();
}

// ═══════════════════════════════════════════
// DENUNCIA — guarda en MySQL vía API
// ═══════════════════════════════════════════
async function registrarDenuncia() {
  const fecha  = document.getElementById('d-fecha').value;
  const hora   = document.getElementById('d-hora').value;
  const delito = document.getElementById('d-delito').value;
  const lugar  = document.getElementById('d-lugar').value;
  const desc   = document.getElementById('d-desc').value.trim();
  const dir    = document.getElementById('d-dir').value.trim();
  const com    = document.getElementById('d-comuna').value;

  document.getElementById('denuncia-ok').classList.remove('show');
  document.getElementById('denuncia-err').classList.remove('show');

  if (!fecha || !hora || !delito || !lugar || !desc || !dir || !com) {
    document.getElementById('denuncia-err').classList.add('show');
    return;
  }

  try {
    await DenunciasService.create({
      fecha,
      hora:        hora + ':00',
      descripcion: `${delito} — ${desc} (Dir: ${dir}, ${com})`,
      fk_id_lugar: parseInt(lugar)
    });

    document.getElementById('denuncia-ok').classList.add('show');
    ['d-fecha','d-hora','d-delito','d-lugar','d-desc','d-dir','d-comuna']
      .forEach(id => document.getElementById(id).value = '');
    document.getElementById('d-fecha').valueAsDate = new Date();
    showToast('Denuncia registrada en la base de datos ✅', 'success');

  } catch (err) {
    document.getElementById('denuncia-err').textContent = '⚠️ Error al guardar: ' + err.message;
    document.getElementById('denuncia-err').classList.add('show');
  }
}

// ═══════════════════════════════════════════
// ADMIN — mapeo tabla → servicio API
// ═══════════════════════════════════════════
const ADMIN_SCHEMAS = {
  tipos:       { title: 'Tabla Tipo',          fields: ['tipo'],                              labels: ['Tipo'],                                       headers: ['ID','Tipo'],                                    pk: 'id_tipo'        },
  delitos:     { title: 'Tabla Delito',         fields: ['tipo_delito'],                       labels: ['Tipo de Delito'],                              headers: ['ID','Tipo Delito'],                              pk: 'id_delito'      },
  riesgos:     { title: 'Tabla Riesgo',         fields: ['nivel','descripcion'],               labels: ['Nivel','Descripción'],                         headers: ['ID','Nivel','Descripción'],                      pk: 'id_nivel'       },
  zonas:       { title: 'Tabla Zona',           fields: ['zona','barrio','fk_tipo'],           labels: ['Zona','Barrio/Vereda','FK Tipo'],               headers: ['ID','Zona','Barrio','FK Tipo'],                  pk: 'id_zona'        },
  ubicaciones: { title: 'Tabla Ubicación',      fields: ['comuna','nombre','puntos_cardinales','tipo','fk_id_zona','fk_id_nivel'], labels: ['Comuna','Nombre','Punto Cardinal','Tipo','FK Zona','FK Nivel'], headers: ['ID','Comuna','Nombre','Punto Cardinal','Tipo','FK Zona','FK Nivel'], pk: 'id_ubicacion' },
  lugares:     { title: 'Lugar de Denuncia',    fields: ['nombre','telefono','direccion','fk_id_ubicacion'], labels: ['Nombre','Teléfono','Dirección','FK Ubicación'], headers: ['ID','Nombre','Teléfono','Dirección','FK Ubicación'], pk: 'id_lugar'  },
  denuncias:   { title: 'Tabla Denuncia',       fields: ['fecha','hora','descripcion','fk_id_lugar'], labels: ['Fecha','Hora','Descripción','FK Lugar'], headers: ['ID','Fecha','Hora','Descripción','FK Lugar'],    pk: 'id_denuncia'    },
  usuarios:    { title: 'Usuarios del Sistema', fields: ['username','rol','nivel'],            labels: ['Usuario','Rol','Nivel'],                       headers: ['ID','Usuario','Rol','Nivel'],                    pk: 'id_usuario'     },
  comentarios: { title: 'Comentarios',          fields: ['usuario','texto','fecha'],           labels: ['Usuario','Texto','Fecha'],                     headers: ['ID','Usuario','Texto','Fecha'],                  pk: 'id_comentario'  },
};

// Relaciona cada pestaña con su servicio de la API
const API_SERVICES = {
  tipos:       TiposService,
  delitos:     DelitosService,
  riesgos:     RiesgosService,
  zonas:       ZonasService,
  ubicaciones: UbicacionesService,
  lugares:     LugaresService,
  denuncias:   DenunciasService,
  usuarios:    UsuariosService,
  comentarios: ComentariosService,
};

function adminTab(tab) {
  currentAdminTab = tab;
  document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.sidebar-btn').forEach(b => {
    if (
      (tab==='tipos'       && b.textContent.includes('Tipo'))        ||
      (tab==='delitos'     && b.textContent.includes('Delito'))      ||
      (tab==='riesgos'     && b.textContent.includes('Riesgo'))      ||
      (tab==='zonas'       && b.textContent.includes('Zona'))        ||
      (tab==='ubicaciones' && b.textContent.includes('Ubicación'))   ||
      (tab==='lugares'     && b.textContent.includes('Lugar'))       ||
      (tab==='denuncias'   && b.textContent.includes('Denuncia'))    ||
      (tab==='usuarios'    && b.textContent.includes('Usuarios'))    ||
      (tab==='comentarios' && b.textContent.includes('Comentario'))
    ) b.classList.add('active');
  });
  renderAdminTable(tab);
}

async function renderAdminTable(tab) {
  const sch     = ADMIN_SCHEMAS[tab];
  const service = API_SERVICES[tab];
  const canEdit = currentUser && currentUser.nivel >= 3;
  const canAdd  = currentUser && currentUser.nivel >= 3;
  const canDel  = currentUser && currentUser.nivel >= 3;

  document.getElementById('admin-content').innerHTML = `
    <div class="card-header">
      <span class="card-title">${sch.title}</span>
      ${canAdd ? `<button class="btn-sm btn-edit" style="padding:0.4rem 1rem" onclick="newRow('${tab}')">+ Nuevo Registro</button>` : ''}
    </div>
    <div style="padding:1rem;color:#666">Cargando datos…</div>
  `;

  try {
    const rows = await service.getAll();
    const header = sch.headers.map(h => `<th>${h}</th>`).join('') + '<th>Acciones</th>';
    const pk     = sch.pk;

    const body = rows.map(row => {
      // Mostrar todas las columnas que existen en el objeto
      const cells = Object.values(row).map(v => `<td>${v ?? ''}</td>`).join('');
      const id    = row[pk];
      const actions = `
        <td><div class="table-actions">
          ${canEdit ? `<button class="btn-sm btn-edit" onclick="editRow('${tab}',${id})">Editar</button>` : ''}
          ${canDel  ? `<button class="btn-sm btn-delete" onclick="askDelete('${tab}',${id})">Eliminar</button>` : ''}
        </div></td>`;
      return `<tr>${cells}${actions}</tr>`;
    }).join('');

    document.getElementById('admin-content').innerHTML = `
      <div class="card-header">
        <span class="card-title">${sch.title}</span>
        ${canAdd ? `<button class="btn-sm btn-edit" style="padding:0.4rem 1rem" onclick="newRow('${tab}')">+ Nuevo Registro</button>` : ''}
      </div>
      <div style="overflow-x:auto">
        <table class="data-table">
          <thead><tr>${header}</tr></thead>
          <tbody>${body || '<tr><td colspan="10" style="text-align:center;color:#666;padding:2rem">Sin registros</td></tr>'}</tbody>
        </table>
      </div>
    `;
  } catch (err) {
    document.getElementById('admin-content').innerHTML = `
      <div class="card-header"><span class="card-title">${sch.title}</span></div>
      <p style="color:#f87171;padding:1.5rem">Error cargando datos: ${err.message}</p>
    `;
  }
}

// ── Modal NUEVO registro ───────────────────
function newRow(tab) {
  editTarget = { tab, id: null };
  const sch = ADMIN_SCHEMAS[tab];
  document.getElementById('modal-form-title').textContent = 'Nuevo Registro — ' + sch.title;
  document.getElementById('modal-form-body').innerHTML = sch.fields.map((f,i) => `
    <div class="form-group">
      <label>${sch.labels[i]}</label>
      <input type="text" id="mf-${f}" placeholder="${sch.labels[i]}">
    </div>
  `).join('');
  document.getElementById('modal-form').classList.add('open');
}

// ── Modal EDITAR registro ──────────────────
async function editRow(tab, id) {
  editTarget = { tab, id };
  const sch     = ADMIN_SCHEMAS[tab];
  const service = API_SERVICES[tab];

  try {
    const row = await service.getById(id);
    document.getElementById('modal-form-title').textContent = 'Editar Registro — ' + sch.title;
    document.getElementById('modal-form-body').innerHTML = sch.fields.map((f,i) => `
      <div class="form-group">
        <label>${sch.labels[i]}</label>
        <input type="text" id="mf-${f}" value="${row[f] ?? ''}">
      </div>
    `).join('');
    document.getElementById('modal-form').classList.add('open');
  } catch (err) {
    showToast('Error cargando registro: ' + err.message, 'error');
  }
}

// ── Guardar (crear o actualizar) ──────────
async function saveModal() {
  if (!editTarget) return;
  const { tab, id } = editTarget;
  const sch     = ADMIN_SCHEMAS[tab];
  const service = API_SERVICES[tab];

  const vals = {};
  sch.fields.forEach(f => {
    const el = document.getElementById('mf-' + f);
    if (el) vals[f] = el.value.trim();
  });

  try {
    if (id === null) {
      await service.create(vals);
      showToast('Registro creado en la base de datos ✅', 'success');
    } else {
      await service.update(id, vals);
      showToast('Registro actualizado ✅', 'success');
    }
    closeModal('modal-form');
    renderAdminTable(tab);
  } catch (err) {
    showToast('Error al guardar: ' + err.message, 'error');
  }
}

// ── Confirmar eliminación ─────────────────
function askDelete(tab, id) {
  deleteTarget = { tab, id };
  document.getElementById('modal-confirm').classList.add('open');
}

async function confirmDelete() {
  if (!deleteTarget) return;
  const { tab, id } = deleteTarget;
  const service = API_SERVICES[tab];

  try {
    await service.remove(id);
    showToast('Registro eliminado de la base de datos', 'error');
    closeModal('modal-confirm');
    renderAdminTable(tab);
  } catch (err) {
    showToast('Error al eliminar: ' + err.message, 'error');
  }
  deleteTarget = null;
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

// ═══════════════════════════════════════════
// TOAST
// ═══════════════════════════════════════════
function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  t.textContent = (type === 'success' ? '✅ ' : '❌ ') + msg;
  t.className   = 'toast ' + type + ' show';
  setTimeout(() => t.classList.remove('show'), 3000);
}

// ═══════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('d-fecha').valueAsDate = new Date();
  setTimeout(() => {
    initMap('mapa-cali', map1, m => map1 = m);
    initChatWelcome();
  }, 200);
});