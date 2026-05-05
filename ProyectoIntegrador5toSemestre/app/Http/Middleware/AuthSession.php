<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: AuthSession
 *
 * Verifica que exista una sesión iniciada (session('logged_user')).
 * Si no existe, redirige al login con un mensaje.
 *
 * Uso en rutas:
 *   Route::middleware('auth.session')->group(function () { ... });
 */
class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('logged_user')) {
            return redirect()
                ->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        return $next($request);
    }
}
