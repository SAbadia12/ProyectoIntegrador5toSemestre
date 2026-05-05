<?php

use App\Http\Controllers\NivelRiesgoController;
use App\Http\Controllers\PuntoCardinalController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ZonasTipoController;
use App\Http\Controllers\ZonasController;
use App\Http\Controllers\SubzonasTipoController;
use App\Http\Controllers\SubzonasController;
use App\Models\NivelRiesgo;
use App\Models\PuntoCardinal;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\ZonasTipo;
use App\Models\Zonas;
use App\Models\SubzonasTipo;
use App\Models\Subzonas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('index');
})->name('home');

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

    session(['logged_user' => $user->id_usuario, 'logged_user_name' => $user->nombre, 'logged_user_lastname' => $user->apellido, 'logged_user_image' => $user->imagen]);

    return redirect()->route('plantilla');
})->name('login.post');

Route::get('/plantilla', function () {
    if (! session()->has('logged_user')) {
        return redirect()->route('login');
    }

    return view('layouts.plantilla');
})->name('plantilla');

Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('home');
})->name('logout');

Route::view('/visitante', 'visitante')->name('visitante');

Route::resource('/nivel', NivelRiesgoController::class);
Route::resource('/cardinal', PuntoCardinalController::class);
Route::resource('/rol', RolController::class);
Route::resource('/usuario', UsuarioController::class);
Route::resource('/zonas_tipo', ZonasTipoController::class);
Route::resource('/zonas', ZonasController::class);
Route::resource('/subzonas_tipo', SubzonasTipoController::class);
Route::resource('/subzonas', SubzonasController::class);


Route::get('/export/punto_cardinal', function() {
    return app(PdfController::class)->export(PuntoCardinal::class, 'Puntos Cardinales', ['ID', 'Nombre'], ['id_punto_cardinal', 'nombre'], 'puntos_cardinales.pdf');
});

Route::get('/export/nivel_riesgo', function() {
    return app(PdfController::class)->export(NivelRiesgo::class, 'Niveles de Riesgo', ['ID', 'Nivel', 'Color'], ['id_nivel_riesgo', 'nivel', 'color'], 'niveles.pdf');
});

Route::get('/export/rol', function() {
    return app(PdfController::class)->export(Rol::class, 'Roles', ['ID', 'Rol'], ['id_rol', 'rol'], 'roles.pdf');
});

Route::get('/export/usuario', function() {
    $usuarios = Usuario::with('rolRelacion')->get()->map(function($u) {
        $u->rol = $u->rolRelacion->rol ?? 'Sin rol';
        return $u;
    });
    
    return app(PdfController::class)->export(Usuario::class, 'Usuarios', 
        ['ID', 'Nombre', 'Apellido', 'Email', 'Rol'], 
        ['id_usuario', 'nombre', 'apellido', 'email', 'rol'], 
        'usuarios.pdf',
        $usuarios  // ← pasas la colección ya preparada
    );
});

Route::get('/export/zonas_tipo', function() {
    return app(PdfController::class)->export(ZonasTipo::class, 'Tipos de Zona', ['ID', 'Tipo'], ['id_tipo', 'tipo'], 'zonas_tipos.pdf');
});

Route::get('/export/zonas', function() {
    $zonas = Zonas::with('zonasTipo')->get()->map(function($z) {
        $z->tipo = $z->zonasTipo->tipo ?? 'Sin tipo';
        return $z;
    });
    
    return app(PdfController::class)->export(Zonas::class, 'Zonas', 
        ['ID', 'Zona', 'Tipo'], 
        ['id_zona', 'zona', 'tipo'], 
        'zonas.pdf',
        $zonas
    );
});

Route::get('/export/subzonas_tipo', function() {
    return app(PdfController::class)->export(SubzonasTipo::class, 'Subtipos de Zona', ['ID', 'Subtipo'], ['id_subtipo', 'subtipo'], 'subzonas_tipos.pdf');
});

Route::get('/export/subzonas', function() {
    $subzonas = Subzonas::with(['zona', 'subzonasTipo'])->get()->map(function ($s) {
        $s->zona = $s->zona->zona ?? 'Sin zona';
        $s->subtipo = $s->subzonasTipo->subtipo ?? 'Sin subtipo';
        return $s;
    });
    
    return app(PdfController::class)->export(Subzonas::class, 'Subzonas', 
        ['ID', 'Subzona', 'Zona', 'Subtipo'], 
        ['id_subzona', 'subzona', 'zona', 'subtipo'], 
        'subzonas.pdf',
        $subzonas
    );
});

