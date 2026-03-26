<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($role === 'superadmin' && !$user->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Se requieren permisos de superadministrador.');
        }

        if ($role === 'client' && !$user->isClient()) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
