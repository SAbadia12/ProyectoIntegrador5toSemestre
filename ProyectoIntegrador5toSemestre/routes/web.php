<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DelitoController;
use App\Http\Controllers\EstacionPoliciaController;
use App\Http\Controllers\NivelRiesgoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PuntoCardinalController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SubzonasController;
use App\Http\Controllers\SubzonasTipoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ZonasController;
use App\Http\Controllers\ZonasTipoController;
use App\Models\NivelRiesgo;
use App\Models\PuntoCardinal;
use App\Models\Rol;
use App\Models\Subzonas;
use App\Models\SubzonasTipo;
use App\Models\Ubicacion;
use App\Models\Usuario;
use App\Models\Zonas;
use App\Models\ZonasTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/visitante', function () {
    $stats = [
        'total_delitos'     => \App\Models\Delito::count(),
        'homicidios'        => \App\Models\Delito::where('tipo', 'Homicidio')->count(),
        'hurto_personas'    => \App\Models\Delito::where('tipo', 'Hurto a personas')->count(),
        'hurto_vehiculos'   => \App\Models\Delito::where('tipo', 'Hurto a vehículos')->count(),
        'hurto_residencias' => \App\Models\Delito::where('tipo', 'Hurto a residencias')->count(),
        'estaciones'        => \App\Models\EstacionPolicia::count(),
        'zonas_cubiertas'   => \App\Models\Zonas::count(),
        'delitos_mes'       => \DB::table('delito_ubicacion')
                                   ->whereMonth('fecha', now()->month)
                                   ->whereYear('fecha', now()->year)->count(),
        'delitos_semana'    => \DB::table('delito_ubicacion')
                                   ->whereBetween('fecha', [
                                       now()->startOfWeek(), now()->endOfWeek()
                                   ])->count(),
    ];

    // Ubicaciones con sus relaciones para el mapa de calor
    // Solo las que tienen coordenadas (lat/lng definidas)
    $ubicaciones = \App\Models\Ubicacion::with(['nivel', 'subzona', 'puntoCardinal'])
        ->whereNotNull('latitud')
        ->whereNotNull('longitud')
        ->get()
        ->map(function ($u) {
            // Extrae el barrio del último segmento de la dirección
            // Ej: "Cl. 73 #28C-15, El Poblado" -> "El Poblado"
            $barrio = null;
            if ($u->direccion && str_contains($u->direccion, ',')) {
                $partes = array_map('trim', explode(',', $u->direccion));
                $barrio = end($partes) ?: null;
            }

            return [
                'id'             => $u->id_ubicacion,
                'direccion'      => $u->direccion,
                'barrio'         => $barrio ?: ($u->subzona->subzona ?? 'Sin barrio'),
                'lat'            => (float) $u->latitud,
                'lng'            => (float) $u->longitud,
                'nivel_nombre'   => $u->nivel->nivel ?? 'Sin nivel',
                'nivel_color'    => $u->nivel->color ?? '#94a3b8',
                'zona_nombre'    => $u->subzona->subzona ?? '—',
                'punto_cardinal' => $u->puntoCardinal->nombre ?? '—',
            ];
        });

    // Estaciones de policía para mostrar en el mapa
    $estacionesPolicia = \App\Models\EstacionPolicia::all()->map(function ($e) {
        return [
            'nombre'    => $e->nombre,
            'direccion' => $e->direccion,
            'telefono'  => $e->telefono,
            'lat'       => (float) $e->latitud,
            'lng'       => (float) $e->longitud,
        ];
    });

    /*
     |─────────────────────────────────────────────────────────
     | Respuestas dinámicas del chat (calculadas desde la BD)
     |─────────────────────────────────────────────────────────
     */

    // Helper: extrae el barrio del campo dirección.
    // "Cl. 73 #28C-15, El Poblado" -> "El Poblado"
    $extraerBarrio = function ($direccion) {
        if (! $direccion || ! str_contains($direccion, ',')) {
            return $direccion;
        }
        $partes = array_map('trim', explode(',', $direccion));
        return end($partes);
    };

    // Barrios con riesgo BAJO (extraídos de dirección)
    $barriosSeguros = \App\Models\Ubicacion::where('id_nivel', 3)
        ->get()
        ->map(fn ($u) => $extraerBarrio($u->direccion))
        ->filter()
        ->unique()
        ->values()
        ->take(8)
        ->toArray();

    // Barrios con riesgo ALTO (extraídos de dirección)
    $altoRiesgo = \App\Models\Ubicacion::where('id_nivel', 1)
        ->get()
        ->map(fn ($u) => $extraerBarrio($u->direccion))
        ->filter()
        ->unique()
        ->values()
        ->take(8)
        ->toArray();

    // Lugares para denunciar = primeras 5 estaciones
    $denunciar = \App\Models\EstacionPolicia::limit(5)->get()->map(function ($e) {
        return ['nombre' => $e->nombre, 'direccion' => $e->direccion, 'telefono' => $e->telefono];
    })->toArray();

    $topDelitos = \App\Models\Delito::select('tipo', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
        ->groupBy('tipo')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->map(fn ($d) => ['tipo' => $d->tipo, 'total' => $d->total]);

    $medioRiesgo = \App\Models\Ubicacion::where('id_nivel', 2)
        ->get()
        ->map(fn ($u) => $extraerBarrio($u->direccion))
        ->filter()
        ->unique()
        ->values()
        ->take(8)
        ->toArray();

    $chatData = [
        'barrios_seguros' => $barriosSeguros,
        'medio_riesgo'    => $medioRiesgo,
        'alto_riesgo'     => $altoRiesgo,
        'top_delitos'     => $topDelitos,
        'denunciar'       => $denunciar,
    ];

    return view('visitante', compact('stats', 'ubicaciones', 'estacionesPolicia', 'chatData'));
})->name('visitante');

// Líneas de emergencia (RF22) - vista pública
Route::view('/emergencias', 'emergencias')->name('emergencias');

// Comentarios (RF5) - endpoint público para que cualquier visitante deje comentario
Route::post('/comentario', [ComentarioController::class, 'store'])->name('comentario.store');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = Usuario::where('email', $credentials['email'])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return back()->withInput()->with('error', 'Credenciales incorrectas.');
    }

    // Bloquear acceso a usuarios desactivados (RF16)
    if (isset($user->activo) && (int) $user->activo === 0) {
        return back()->withInput()->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
    }

    session([
        'logged_user'          => $user->id_usuario,
        'logged_user_name'     => $user->nombre,
        'logged_user_lastname' => $user->apellido,
        'logged_user_image'    => $user->imagen,
        'logged_user_rol'      => $user->rol,
    ]);

    return redirect()->route('plantilla');
})->name('login.post');

Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('home');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Internas (requieren login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth.session')->group(function () {

    // Dashboard principal (RF10)
    Route::get('/plantilla', [DashboardController::class, 'index'])->name('plantilla');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Niveles de Riesgo y estadísticas - Analista y Administrador
    Route::middleware('role:analista,admin')->group(function () {
        Route::resource('/nivel', NivelRiesgoController::class);
        Route::resource('/cardinal', PuntoCardinalController::class);
        Route::resource('/zonas_tipo', ZonasTipoController::class);
        Route::resource('/zonas', ZonasController::class)->parameters(['zonas' => 'zonas']);
        Route::resource('/subzonas_tipo', SubzonasTipoController::class);
        Route::resource('/subzonas', SubzonasController::class)->parameters(['subzonas' => 'subzonas']);
        Route::resource('/ubicacion', UbicacionController::class);
        Route::resource('/delitos', DelitoController::class)->parameters(['delitos' => 'delito']);
        Route::resource('/estaciones', EstacionPoliciaController::class)->parameters(['estaciones' => 'estacion']);
    });

    // Bandeja de comentarios - Moderador y Administrador (RF11, RF13)
    Route::middleware('role:moderador,admin')->group(function () {
        Route::get('/comentario', [ComentarioController::class, 'index'])->name('comentario.index');
        Route::patch('/comentario/{comentario}/aprobar',  [ComentarioController::class, 'aprobar'])->name('comentario.aprobar');
        Route::patch('/comentario/{comentario}/rechazar', [ComentarioController::class, 'rechazar'])->name('comentario.rechazar');
        Route::delete('/comentario/{comentario}',          [ComentarioController::class, 'destroy'])->name('comentario.destroy');
    });

    // Gestión de Roles - solo Administrador
    Route::middleware('role:admin')->group(function () {
        Route::resource('/rol', RolController::class);
    });

    // Gestión de Usuarios - solo Administrador (RF3, RF15, RF16, RF17)
    Route::middleware('role:admin')->group(function () {
        Route::resource('/usuario', UsuarioController::class);
        Route::patch('/usuario/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])
            ->name('usuario.toggleActivo');
    });

    /*
    |----------------------------------------------------------------------
    | Exportación a PDF
    |----------------------------------------------------------------------
    */

    Route::get('/export/punto_cardinal', function () {
        return app(PdfController::class)->export(
            PuntoCardinal::class,
            'Puntos Cardinales',
            ['ID', 'Nombre'],
            ['id_punto_cardinal', 'nombre'],
            'puntos_cardinales.pdf'
        );
    });

    Route::get('/export/nivel_riesgo', function () {
        return app(PdfController::class)->export(
            NivelRiesgo::class,
            'Niveles de Riesgo',
            ['ID', 'Nivel', 'Color'],
            ['id_nivel_riesgo', 'nivel', 'color'],
            'niveles.pdf'
        );
    });

    Route::get('/export/rol', function () {
        return app(PdfController::class)->export(
            Rol::class,
            'Roles',
            ['ID', 'Rol'],
            ['id_rol', 'rol'],
            'roles.pdf'
        );
    })->middleware('role:admin');

    Route::get('/export/usuario', function () {
        $usuarios = Usuario::with('rolRelacion')->get()->map(function ($u) {
            $u->rol = $u->rolRelacion->rol ?? 'Sin rol';
            return $u;
        });

        return app(PdfController::class)->export(
            Usuario::class,
            'Usuarios',
            ['ID', 'Nombre', 'Apellido', 'Email', 'Rol'],
            ['id_usuario', 'nombre', 'apellido', 'email', 'rol'],
            'usuarios.pdf',
            $usuarios
        );
    })->middleware('role:admin');

    Route::get('/export/zonas_tipo', function () {
        return app(PdfController::class)->export(
            ZonasTipo::class,
            'Tipos de Zona',
            ['ID', 'Tipo'],
            ['id_tipo', 'tipo'],
            'zonas_tipos.pdf'
        );
    });

    Route::get('/export/zonas', function () {
        $zonas = Zonas::with('zonasTipo')->get()->map(function ($z) {
            $z->tipo = $z->zonasTipo->tipo ?? 'Sin tipo';
            return $z;
        });

        return app(PdfController::class)->export(
            Zonas::class,
            'Zonas',
            ['ID', 'Zona', 'Tipo'],
            ['id_zona', 'zona', 'tipo'],
            'zonas.pdf',
            $zonas
        );
    });

    Route::get('/export/subzonas_tipo', function () {
        return app(PdfController::class)->export(
            SubzonasTipo::class,
            'Subtipos de Zona',
            ['ID', 'Subtipo'],
            ['id_subtipo', 'subtipo'],
            'subzonas_tipos.pdf'
        );
    });

    Route::get('/export/subzonas', function () {
        $subzonas = Subzonas::with(['zona', 'subzonasTipo'])->get()->map(function ($s) {
            $s->zona = $s->zona->zona ?? 'Sin zona';
            $s->subtipo = $s->subzonasTipo->subtipo ?? 'Sin subtipo';
            return $s;
        });

        return app(PdfController::class)->export(
            Subzonas::class,
            'Subzonas',
            ['ID', 'Subzona', 'Zona', 'Subtipo'],
            ['id_subzona', 'subzona', 'zona', 'subtipo'],
            'subzonas.pdf',
            $subzonas
        );
    });

    Route::get('/export/ubicacion', function () {
        $ubicaciones = Ubicacion::with(['nivel', 'puntoCardinal', 'subzona'])->get()->map(function ($u) {
            $u->nivel_nombre   = $u->nivel->nivel ?? 'Sin nivel';
            $u->punto_nombre   = $u->puntoCardinal->nombre ?? 'Sin punto';
            $u->zona_nombre    = $u->subzona->subzona ?? 'Sin subzona';
            return $u;
        });

        return app(PdfController::class)->export(
            Ubicacion::class,
            'Ubicaciones',
            ['ID', 'Dirección', 'Nivel', 'Punto Cardinal', 'Subzona'],
            ['id_ubicacion', 'direccion', 'nivel_nombre', 'punto_nombre', 'zona_nombre'],
            'ubicaciones.pdf',
            $ubicaciones
        );
    });

});
