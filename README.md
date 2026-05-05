# SISC — Sistema de Información de Seguridad Ciudadana

<p align="center">
  <img src="public/img/logoSISC.jpeg" alt="Logo SISC" width="120">
</p>

<p align="center">
  Plataforma web para visualizar, consultar y reportar información de seguridad ciudadana en la ciudad de Cali, Valle del Cauca.
</p>

<p align="center">
  <strong>Proyecto Integrador · 5to Semestre — Ingeniería de Software</strong><br>
  Programación III · Ingeniería de Software II
</p>

---

## 📋 Sobre el proyecto

SISC es un sistema web orientado a centralizar información sobre seguridad pública en la ciudad de Cali. Permite a ciudadanos y visitantes:

- Consultar **niveles de riesgo** por zona y barrio mediante un mapa interactivo de calor.
- Identificar la **ubicación de estaciones de policía** y obtener su dirección y teléfono.
- Acceder a **líneas de emergencia** nacionales y locales.
- Dejar **comentarios y retroalimentación** sobre la plataforma.
- Consultar información rápida mediante un **chat de consultas** con respuestas basadas en datos reales.

Internamente, el sistema incluye un **panel administrativo** con dashboard de métricas, gestión de usuarios, niveles de riesgo, zonas, ubicaciones y moderación de contenido.

## 👥 Equipo

| Integrante |
|---|
| Anyelo Gustavo Chitán Hernández |
| Tania Isadora Mora Pedrero |
| Sebastián Abadía |
| Juan Sebastián Almendra |
| Daniel Campo |

## 🛠 Tecnologías

| Capa | Stack |
|---|---|
| Backend | PHP 8.2+ · Laravel 12 |
| Frontend | Blade · HTML5 · CSS3 · JavaScript ES6 |
| Base de datos | MySQL 8 (producción) · SQLite en memoria (pruebas) |
| Mapa | Leaflet 1.9 + OpenStreetMap (CARTO Dark) |
| Gráficos | Chart.js 4.4 |
| Generación PDF | barryvdh/laravel-dompdf 3 |
| Pruebas | PHPUnit 11 |
| Servidor local | XAMPP / Laravel Artisan |
| Metodología | Scrum |

## 🚀 Instalación

### Prerrequisitos

- PHP ≥ 8.2 con extensiones: `mbstring`, `xml`, `pdo_mysql`, `gd`
- Composer
- Node.js ≥ 18 + npm
- MySQL 8 (vía XAMPP, Laragon, etc.) corriendo en el puerto 3306

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repo>
cd ProyectoIntegrador5toSemestre/ProyectoIntegrador5toSemestre

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias frontend
npm install
npm run build

# 4. Copiar archivo de entorno y generar APP_KEY
cp .env.example .env
php artisan key:generate

# 5. Configurar la BD en .env (ajusta según tu instalación)
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=sisc
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Crear la base de datos en MySQL
#    En phpMyAdmin: Crear nueva BD llamada "sisc" con utf8mb4_unicode_ci

# 7. Correr migraciones
php artisan migrate

# 8. Cargar datos iniciales
php artisan db:seed

# 9. Levantar el servidor
php artisan serve
```

La app queda disponible en `http://localhost:8000`.

## 🔑 Credenciales de prueba

Tras correr `db:seed` se crean estos usuarios:

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `admin@sisc.com` | `admin123` |
| Moderador de Contenido | `moderador@sisc.com` | `moderador123` |
| Analista de Seguridad | `analista@sisc.com` | `analista123` |
| Usuario inactivo (para pruebas RF16) | `inactivo@sisc.com` | `inactivo123` |

## ✅ Funcionalidades implementadas

### Públicas (sin autenticación)

| ID | Descripción |
|---|---|
| RF4 | Chat de consultas con preguntas pre-establecidas (datos reales desde MySQL) |
| RF5 | Visitante puede dejar comentarios sobre la plataforma |
| RF6 | Mapa de calor de Cali con ubicaciones coloreadas según nivel de riesgo |
| RF7 | Visualización de estaciones de policía en el mapa |
| RF12 | Pop-up con dirección y teléfono al hacer clic en una estación |
| RF22 | Botón de líneas de emergencia con números nacionales y locales |

### Autenticación y autorización

| ID | Descripción |
|---|---|
| RF1 | Login para usuarios con roles internos |
| RF2 | Validación de credenciales |
| RF8 | Cierre de sesión |
| RF16 | Bloqueo de login para usuarios desactivados |

### Administrador

| ID | Descripción |
|---|---|
| RF3 | Crear usuarios con rol interno |
| RF15 | Listar todos los usuarios con su rol |
| RF16 | Activar/desactivar usuarios sin eliminarlos |
| RF17 | Editar información de usuarios |

### Moderador de Contenido

| ID | Descripción |
|---|---|
| RF11 | Bandeja de moderación de comentarios |
| RF13 | Eliminar comentarios inapropiados |
| RF18-21 | CRUD de niveles de riesgo |

### Analista de Seguridad

| ID | Descripción |
|---|---|
| RF10 | Dashboard con KPIs, distribución de delitos, tendencia mensual y top 5 zonas |

### Pendientes para versiones futuras

- RF9 — Cierre de sesión por inactividad de 5 minutos
- RF14 — Restablecer contraseña por correo

## 📂 Estructura del proyecto

```
ProyectoIntegrador5toSemestre/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # CRUDs (Usuario, Rol, Ubicacion, Comentario, Dashboard, etc.)
│   │   ├── Middleware/         # AuthSession, CheckRole
│   │   └── Requests/           # Validaciones
│   └── Models/                 # Eloquent (Usuario, Comuna, Delito, Ubicacion, etc.)
├── database/
│   ├── migrations/             # Esquema de BD
│   └── seeders/                # Datos iniciales (22 comunas, ubicaciones, delitos, usuarios)
├── public/
│   ├── css/                    # Estilos del admin y visitante
│   ├── js/                     # Scripts cliente (sis-cali.js, api.js)
│   └── img/                    # Logos e iconos
├── resources/
│   └── views/                  # Plantillas Blade
│       ├── layouts/plantilla.blade.php   # Layout admin
│       ├── visitante.blade.php           # Vista pública con mapa
│       ├── emergencias.blade.php         # Líneas de emergencia
│       ├── dashboard/                    # Dashboard
│       ├── usuario/, rol/, nivel/...     # CRUDs
│       └── comentario/                   # Moderación
├── routes/
│   └── web.php                 # Rutas públicas, auth y por rol
├── tests/
│   ├── Unit/                   # Pruebas unitarias
│   └── Feature/                # Pruebas funcionales (HTTP)
└── README.md
```

## 🧪 Pruebas

El proyecto incluye pruebas automatizadas con PHPUnit en `tests/`. Las pruebas usan SQLite en memoria, no afectan la base de datos real.

```bash
# Correr todas las pruebas
php artisan test

# Solo unitarias
php artisan test --testsuite=Unit

# Solo funcionales
php artisan test --testsuite=Feature

# Una clase específica
php artisan test --filter AuthTest
```

Pruebas incluidas:

| Archivo | Cubre |
|---|---|
| `tests/Unit/ComentarioTest.php` | Modelo Comentario, scopes y constantes |
| `tests/Feature/AuthTest.php` | Login, RF16 inactivos, hash de password |
| `tests/Feature/ComentarioPublicoTest.php` | RF5: validaciones y filtro anti-lenguaje |
| `tests/Feature/MiddlewareTest.php` | `auth.session` y `role` protegen rutas correctamente |

## 🎨 Roles y permisos

| Rol | ID | Acceso |
|---|---|---|
| Administrador | 1 | Todo: usuarios, roles, niveles, zonas, ubicaciones, comentarios, dashboard |
| Moderador de Contenido | 2 | Niveles de riesgo, zonas, comentarios, dashboard |
| Analista de Seguridad | 3 | Dashboard y consultas |
| Usuario / Visitante | — | Mapa público, chat, líneas de emergencia, dejar comentarios |

## 📊 Datos iniciales (seeders)

Al correr `php artisan db:seed` se cargan:

- 3 niveles de riesgo (Bajo/Medio/Alto) con sus colores
- 5 puntos cardinales (N, S, E, O, Centro)
- 2 tipos de zona (Comuna, Corregimiento) y 3 subtipos (Barrio, Vereda, Sector)
- 22 comunas urbanas + 15 corregimientos rurales de Cali
- 35 subzonas (barrios y veredas representativos)
- 30 ubicaciones con coordenadas reales
- 20 estaciones y CAIs de la Policía Nacional
- ~270 delitos distribuidos según el nivel de riesgo de cada comuna
- 4 usuarios de prueba (uno por rol + uno desactivado)

## 🛣 Comandos útiles

```bash
# Limpiar caches
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Recargar datos desde cero (cuidado: borra todo)
php artisan migrate:fresh --seed

# Ejecutar un seeder específico
php artisan db:seed --class=DelitoSeeder

# Crear un usuario admin desde tinker
php artisan tinker
>>> App\Models\Usuario::create([
        'nombre'=>'X','apellido'=>'Y','email'=>'x@y.com',
        'password'=>'12345678','rol'=>1,'activo'=>true,'imagen'=>null
    ]);
```

## 🌐 Rutas principales

| URL | Descripción | Acceso |
|---|---|---|
| `/` | Página de bienvenida | Público |
| `/visitante` | Mapa de calor + chat + estadísticas | Público |
| `/emergencias` | Directorio de líneas de emergencia | Público |
| `/login` | Inicio de sesión | Público |
| `/plantilla` o `/dashboard` | Dashboard del analista | Roles internos |
| `/usuario` | CRUD de usuarios | Admin |
| `/rol` | CRUD de roles | Admin |
| `/nivel` | CRUD de niveles de riesgo | Admin · Moderador |
| `/zonas`, `/zonas_tipo`, `/subzonas`, `/subzonas_tipo` | Gestión de zonificación | Roles internos |
| `/ubicacion` | CRUD de ubicaciones (entidad central del MER) | Roles internos |
| `/comentario` | Bandeja de moderación de comentarios | Admin · Moderador |
| `/cardinal` | CRUD de puntos cardinales | Roles internos |
| `/export/{recurso}` | Generación de PDF | Según rol |

## 📐 Modelo de datos (resumen)

Entidades principales y sus relaciones:

```
nivel_riesgos (1) ───┐
                     ├─→ ubicaciones (N)
puntos_cardinales (1)┤
                     │
zonas (N) ───────────┘  zonas_tipos (1)
   │                       ↑
   │                       │
   └→ subzonas (N) ────────┘
                  │
                  └─→ subzonas_tipos (1)

usuarios (N) ─→ roles (1)

comunas (1) ─→ delitos (N)
comunas (1) ─→ estaciones_policia (N)

comentarios (estado: pendiente/aprobado/rechazado)
```

## 📝 Licencia

Proyecto académico desarrollado con fines educativos. El framework Laravel se distribuye bajo licencia MIT.

## 📞 Contacto

Para reportar problemas o sugerencias, contactar al equipo de desarrollo en la institución educativa.
