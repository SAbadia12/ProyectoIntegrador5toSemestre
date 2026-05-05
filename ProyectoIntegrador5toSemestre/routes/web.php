<?php

use App\Http\Controllers\DashboardController;
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
    // Comunas del Distrito de Aguablanca: 13, 14, 15, 21
    $aguablancaComunas = [13, 14, 15, 21];

    $stats = [
        'homicidios'  => \App\Models\Delito::where('tipo', 'Homicidio')->count(),
        'comunas'     => \App\Models\Comuna::count(),
        'aguablanca'  => \App\Models\Delito::whereIn('id_comuna',
                            \App\Models\Comuna::whereIn('numero', $aguablancaComunas)->pluck('id_comuna')
                        )->count(),
        'estaciones'  => \App\Models\EstacionPolicia::count(),
    ];

    return view('visitante', compact('stats'));
})->name('visitante');

// Líneas de emergencia (RF22) - vista pública
Route::view('/emergencias', 'emergencias')->name('emergencias');

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

    // Niveles de Riesgo - Moderador (RF18-21)
    Route::middleware('role:moderador,admin')->group(function () {
        Route::resource('/nivel', NivelRiesgoController::class);
    });

    // Puntos cardinales - cualquier rol interno
    Route::resource('/cardinal', PuntoCardinalController::class);

    // CRUD de Zonas / Subzonas (entidades de tu compañero)
    Route::resource('/zonas_tipo', ZonasTipoController::class);
    Route::resource('/zonas', ZonasController::class);
    Route::resource('/subzonas_tipo', SubzonasTipoController::class);
    Route::resource('/subzonas', SubzonasController::class);

    // CRUD de Ubicaciones (entidad central del MER)
    Route::resource('/ubicacion', UbicacionController::class);

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
        $ubicaciones = Ubicacion::with(['nivel', 'puntoCardinal', 'zona'])->get()->map(function ($u) {
            $u->nivel_nombre   = $u->nivel->nivel ?? 'Sin nivel';
            $u->punto_nombre   = $u->puntoCardinal->nombre ?? 'Sin punto';
            $u->zona_nombre    = $u->zona->zona ?? 'Sin zona';
            return $u;
        });

        return app(PdfController::class)->export(
            Ubicacion::class,
            'Ubicaciones',
            ['ID', 'Dirección', 'Nivel', 'Punto Cardinal', 'Zona'],
            ['id_ubicacion', 'direccion', 'nivel_nombre', 'punto_nombre', 'zona_nombre'],
            'ubicaciones.pdf',
            $ubicaciones
        );
    });

});
