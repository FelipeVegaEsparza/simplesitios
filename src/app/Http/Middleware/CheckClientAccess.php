<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin tiene acceso a todo
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Cliente solo accede a su propio cliente
        if ($user->isClient()) {
            // Verificar si hay un client_id en la ruta
            $routeClientId = $request->route('client');
            
            if ($routeClientId && $routeClientId != $user->client_id) {
                abort(403, 'No tienes acceso a este cliente.');
            }

            // Verificar acceso a sección
            $section = $request->route('section');
            if ($section && is_object($section)) {
                if ($section->client_id !== $user->client_id) {
                    abort(403, 'No tienes acceso a esta sección.');
                }
            }
        }

        return $next($request);
    }
}
