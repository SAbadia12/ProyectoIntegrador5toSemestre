// api.js — Módulo de servicio para consumir la API REST desde el frontend
// Incluye este archivo en tu HTML con: <script src="api.js"></script>
// Debe ir ANTES de tu script principal.

const API_BASE = 'http://localhost:3000/api'; // Cambia al URL del servidor en producción

// Token JWT almacenado en memoria (no en localStorage por seguridad)
let _token = null;

// ─────────────────────────────────────────────────────────────
//  Utilidad interna para hacer fetch con headers correctos
// ─────────────────────────────────────────────────────────────
async function apiFetch(path, options = {}) {
  const headers = { 'Content-Type': 'application/json' };
  if (_token) headers['Authorization'] = 'Bearer ' + _token;

  const res = await fetch(API_BASE + path, { ...options, headers });
  const data = await res.json();

  if (!res.ok) throw new Error(data.error || 'Error en la solicitud');
  return data;
}

// ─────────────────────────────────────────────────────────────
//  AUTH
// ─────────────────────────────────────────────────────────────
const AuthService = {
  async login(username, password) {
    const data = await apiFetch('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ username, password }),
    });
    _token = data.token;
    return data.user;
  },
  logout() { _token = null; },
  isLoggedIn() { return !!_token; },
};

// ─────────────────────────────────────────────────────────────
//  RECURSOS GENÉRICOS
// ─────────────────────────────────────────────────────────────
function makeService(endpoint) {
  return {
    getAll:    ()       => apiFetch(`/${endpoint}`),
    getById:   (id)     => apiFetch(`/${endpoint}/${id}`),
    create:    (body)   => apiFetch(`/${endpoint}`, { method: 'POST', body: JSON.stringify(body) }),
    update:    (id, body) => apiFetch(`/${endpoint}/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
    remove:    (id)     => apiFetch(`/${endpoint}/${id}`, { method: 'DELETE' }),
  };
}

const TiposService       = makeService('tipos');
const RiesgosService     = makeService('riesgos');
const ZonasService       = makeService('zonas');
const UbicacionesService = makeService('ubicaciones');
const LugaresService     = makeService('lugares');
const DelitosService     = makeService('delitos');
const ComentariosService = makeService('comentarios');
const UsuariosService    = makeService('usuarios');

// ─────────────────────────────────────────────────────────────
//  DENUNCIAS (con endpoints especiales)
// ─────────────────────────────────────────────────────────────
const DenunciasService = {
  getAll:           ()        => apiFetch('/denuncias'),
  getByComuna:      (comuna)  => apiFetch(`/denuncias/comuna/${encodeURIComponent(comuna)}`),
  getDelitosTop:    ()        => apiFetch('/denuncias/estadisticas/delitos-frecuentes'),
  getPorRiesgo:     ()        => apiFetch('/denuncias/estadisticas/por-riesgo'),
  create:           (body)    => apiFetch('/denuncias', { method: 'POST', body: JSON.stringify(body) }),
  update:           (id, body)=> apiFetch(`/denuncias/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
  remove:           (id)      => apiFetch(`/denuncias/${id}`, { method: 'DELETE' }),
};

// ─────────────────────────────────────────────────────────────
//  CONSULTAS INTELIGENTES
// ─────────────────────────────────────────────────────────────
const ConsultasService = {
  barriosPorRiesgo: (nivel) => apiFetch(`/barrios-por-riesgo/${encodeURIComponent(nivel)}`),
  resumenComunas:   ()      => apiFetch('/resumen-comunas'),
};
