<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin no necesita verificación
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Verificar que el cliente del usuario esté activo
        if ($user->client && !$user->client->isActive()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Tu cuenta ha sido suspendida. Contacta al administrador.');
        }

        return $next($request);
    }
}
