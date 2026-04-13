<?php

use App\Http\Controllers\NivelRiesgoController;
use App\Http\Controllers\PuntoCardinalController;
use App\Models\NivelRiesgo;
use App\Models\PuntoCardinal;
use App\Models\Usuario;
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


Route::get('/export/punto_cardinal', function() {
    return app(PdfController::class)->export(PuntoCardinal::class, 'Puntos Cardinales', ['ID', 'Nombre'], ['id_punto_cardinal', 'nombre'], 'puntos_cardinales.pdf');
});

Route::get('/export/nivel_riesgo', function() {
    return app(PdfController::class)->export(NivelRiesgo::class, 'Niveles de Riesgo', ['ID', 'Nivel', 'Color'], ['id_nivel_riesgo', 'nivel', 'color'], 'niveles.pdf');
});

