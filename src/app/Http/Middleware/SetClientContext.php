<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->client) {
            // Compartir el cliente actual con todas las vistas
            View::share('currentClient', $user->client);
            
            // También disponible en el request
            $request->attributes->set('current_client_id', $user->client_id);
        }

        return $next($request);
    }
}
