<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API Pública
Route::prefix('public')->name('api.public.')->group(function () {
    
    // Información del cliente
    Route::get('{client}', [PublicApiController::class, 'clientInfo'])
        ->name('client');
    
    // Secciones disponibles
    Route::get('{client}/sections', [PublicApiController::class, 'sections'])
        ->name('sections');
    
    // Contenido de una sección (listado para collection, item único para single)
    Route::get('{client}/{section}', [PublicApiController::class, 'sectionContent'])
        ->name('section');
    
    // Detalle de entrada específica (solo para collection)
    Route::get('{client}/{section}/{slug}', [PublicApiController::class, 'entryDetail'])
        ->name('entry');
    
    // Settings del cliente
    Route::get('{client}/settings', [PublicApiController::class, 'settings'])
        ->name('settings');
});
