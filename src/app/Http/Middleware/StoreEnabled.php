<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin siempre tiene acceso
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Cliente debe tener tienda habilitada
        if ($user->client && !$user->client->isStoreEnabled()) {
            return redirect()->route('client.dashboard')
                ->with('error', 'La tienda online no está habilitada para tu cuenta. Contacta al administrador.');
        }

        return $next($request);
    }
}
