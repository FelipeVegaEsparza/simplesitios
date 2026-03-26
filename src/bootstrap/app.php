<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'client.access' => \App\Http\Middleware\CheckClientAccess::class,
            'client.status' => \App\Http\Middleware\CheckClientStatus::class,
            'client.context' => \App\Http\Middleware\SetClientContext::class,
            'store.enabled' => \App\Http\Middleware\StoreEnabled::class,
        ]);
        
        $middleware->group('client', [
            'auth',
            'client.status',
            'client.access',
            'client.context',
        ]);
        
        $middleware->group('admin', [
            'auth',
            'role:superadmin',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
