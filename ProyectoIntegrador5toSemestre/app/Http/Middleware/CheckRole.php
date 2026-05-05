<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckRole
 *
 * Verifica que el usuario logueado tenga uno de los roles permitidos.
 *
 * Roles del sistema (tabla roles):
 *   1 - Administrador
 *   2 - Moderador de Contenido
 *   3 - Analista de Seguridad
 *
 * Uso en rutas:
 *   Route::middleware('role:admin')->group(...);
 *   Route::middleware('role:admin,moderador')->group(...);
 *   Route::middleware('role:1,2')->group(...); // también acepta IDs directos
 */
class CheckRole
{
    /**
     * Mapa de alias de rol a ID.
     */
    protected array $roleMap = [
        'admin'         => 1,
        'administrador' => 1,
        'moderador'     => 2,
        'analista'      => 3,
    ];

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $idUsuario = $request->session()->get('logged_user');

        if (! $idUsuario) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        $usuario = Usuario::find($idUsuario);

        if (! $usuario) {
            $request->session()->flush();
            return redirect()->route('login')
                ->with('error', 'Sesión inválida.');
        }

        // Convertir roles permitidos (alias o IDs) a un array de IDs
        $idsPermitidos = collect($roles)->map(function ($r) {
            $r = strtolower(trim($r));
            return $this->roleMap[$r] ?? (int) $r;
        })->toArray();

        if (! in_array((int) $usuario->rol, $idsPermitidos, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
