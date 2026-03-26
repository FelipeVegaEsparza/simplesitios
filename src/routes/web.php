<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Rutas públicas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Ruta home redirige según rol
Route::get('/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->getRedirectRoute());
    }
    return redirect()->route('login');
})->name('home');

// Rutas de Admin (Superadmin)
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    
    // Clientes
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);
    Route::get('clients/{client}/sections', [App\Http\Controllers\Admin\ClientController::class, 'sections'])
        ->name('clients.sections');
    
    // Usuarios
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Secciones
    Route::resource('sections', App\Http\Controllers\Admin\SectionController::class);
    Route::get('sections/{section}/fields', [App\Http\Controllers\Admin\SectionController::class, 'fields'])
        ->name('sections.fields');
    
    // Campos de sección
    Route::post('section-fields', [App\Http\Controllers\Admin\SectionFieldController::class, 'store'])
        ->name('section-fields.store');
    Route::put('section-fields/{field}', [App\Http\Controllers\Admin\SectionFieldController::class, 'update'])
        ->name('section-fields.update');
    Route::delete('section-fields/{field}', [App\Http\Controllers\Admin\SectionFieldController::class, 'destroy'])
        ->name('section-fields.destroy');
    Route::post('sections/{section}/fields/reorder', [App\Http\Controllers\Admin\SectionFieldController::class, 'reorder'])
        ->name('sections.fields.reorder');
    
    // Contenido
    Route::get('content-entries', [App\Http\Controllers\Admin\ContentEntryController::class, 'index'])
        ->name('content_entries.index');
    Route::get('clients/{client}/sections/{section}/entries', [App\Http\Controllers\Admin\ContentEntryController::class, 'bySection'])
        ->name('content_entries.by_section');
    Route::get('clients/{client}/sections/{section}/entries/create', [App\Http\Controllers\Admin\ContentEntryController::class, 'create'])
        ->name('content_entries.create');
    Route::post('clients/{client}/sections/{section}/entries', [App\Http\Controllers\Admin\ContentEntryController::class, 'store'])
        ->name('content_entries.store');
    Route::get('clients/{client}/sections/{section}/entries/{entry}/edit', [App\Http\Controllers\Admin\ContentEntryController::class, 'edit'])
        ->name('content_entries.edit');
    Route::put('clients/{client}/sections/{section}/entries/{entry}', [App\Http\Controllers\Admin\ContentEntryController::class, 'update'])
        ->name('content_entries.update');
    Route::delete('clients/{client}/sections/{section}/entries/{entry}', [App\Http\Controllers\Admin\ContentEntryController::class, 'destroy'])
        ->name('content_entries.destroy');
    
    // Configuración
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])
        ->name('settings.update');
    Route::get('settings/remove-logo', [App\Http\Controllers\Admin\SettingsController::class, 'removeLogo'])
        ->name('settings.remove-logo');
    Route::get('settings/remove-favicon', [App\Http\Controllers\Admin\SettingsController::class, 'removeFavicon'])
        ->name('settings.remove-favicon');
});

// Rutas de Cliente
Route::middleware('client')->prefix('client')->name('client.')->group(function () {
    
    // Dashboard
    Route::get('/', [App\Http\Controllers\Client\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index']);
    
    // Perfil
    Route::get('profile', [App\Http\Controllers\Client\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\Client\ProfileController::class, 'update'])
        ->name('profile.update');
    
    // Secciones
    Route::get('sections', [App\Http\Controllers\Client\SectionController::class, 'index'])
        ->name('sections.index');
    Route::get('sections/{section}', [App\Http\Controllers\Client\SectionController::class, 'show'])
        ->name('sections.show');
    
    // Contenido - Secciones tipo collection
    Route::get('sections/{section}/entries', [App\Http\Controllers\Client\ContentEntryController::class, 'index'])
        ->name('sections.entries.index');
    Route::get('sections/{section}/entries/create', [App\Http\Controllers\Client\ContentEntryController::class, 'create'])
        ->name('sections.entries.create');
    Route::post('sections/{section}/entries', [App\Http\Controllers\Client\ContentEntryController::class, 'store'])
        ->name('sections.entries.store');
    Route::get('sections/{section}/entries/{entry}/edit', [App\Http\Controllers\Client\ContentEntryController::class, 'edit'])
        ->name('sections.entries.edit');
    Route::put('sections/{section}/entries/{entry}', [App\Http\Controllers\Client\ContentEntryController::class, 'update'])
        ->name('sections.entries.update');
    Route::delete('sections/{section}/entries/{entry}', [App\Http\Controllers\Client\ContentEntryController::class, 'destroy'])
        ->name('sections.entries.destroy');
    
    // Contenido - Secciones tipo single
    Route::get('sections/{section}/single/edit', [App\Http\Controllers\Client\ContentEntryController::class, 'editSingle'])
        ->name('sections.single.edit');
    Route::put('sections/{section}/single', [App\Http\Controllers\Client\ContentEntryController::class, 'updateSingle'])
        ->name('sections.single.update');
    
    // API Endpoints
    Route::get('api', [App\Http\Controllers\Client\ApiController::class, 'index'])
        ->name('api.index');
    
    // Media
    Route::get('media', [App\Http\Controllers\Client\MediaController::class, 'index'])
        ->name('media.index');
    Route::get('media/create', [App\Http\Controllers\Client\MediaController::class, 'create'])
        ->name('media.create');
    Route::post('media', [App\Http\Controllers\Client\MediaController::class, 'store'])
        ->name('media.store');
    Route::delete('media/{media}', [App\Http\Controllers\Client\MediaController::class, 'destroy'])
        ->name('media.destroy');
    Route::get('media/selector', [App\Http\Controllers\Client\MediaController::class, 'selector'])
        ->name('media.selector');
    
    // Tienda Online (solo si está habilitada)
    Route::middleware('store.enabled')->prefix('store')->name('store.')->group(function () {
        // Productos
        Route::get('products', [App\Http\Controllers\Client\Store\ProductController::class, 'index'])
            ->name('products.index');
        Route::get('products/create', [App\Http\Controllers\Client\Store\ProductController::class, 'create'])
            ->name('products.create');
        Route::post('products', [App\Http\Controllers\Client\Store\ProductController::class, 'store'])
            ->name('products.store');
        Route::get('products/{product}/edit', [App\Http\Controllers\Client\Store\ProductController::class, 'edit'])
            ->name('products.edit');
        Route::put('products/{product}', [App\Http\Controllers\Client\Store\ProductController::class, 'update'])
            ->name('products.update');
        Route::delete('products/{product}', [App\Http\Controllers\Client\Store\ProductController::class, 'destroy'])
            ->name('products.destroy');
        Route::patch('products/{product}/status', [App\Http\Controllers\Client\Store\ProductController::class, 'updateStatus'])
            ->name('products.update-status');
        
        // Categorías
        Route::get('categories', [App\Http\Controllers\Client\Store\CategoryController::class, 'index'])
            ->name('categories.index');
        Route::post('categories', [App\Http\Controllers\Client\Store\CategoryController::class, 'store'])
            ->name('categories.store');
        Route::put('categories/{category}', [App\Http\Controllers\Client\Store\CategoryController::class, 'update'])
            ->name('categories.update');
        Route::delete('categories/{category}', [App\Http\Controllers\Client\Store\CategoryController::class, 'destroy'])
            ->name('categories.destroy');
        Route::post('categories/reorder', [App\Http\Controllers\Client\Store\CategoryController::class, 'reorder'])
            ->name('categories.reorder');
        
        // Órdenes
        Route::get('orders', [App\Http\Controllers\Client\Store\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\Client\Store\OrderController::class, 'show'])
            ->name('orders.show');
        Route::post('orders/{order}/confirm-payment', [App\Http\Controllers\Client\Store\OrderController::class, 'confirmPayment'])
            ->name('orders.confirm-payment');
        Route::post('orders/{order}/mark-processing', [App\Http\Controllers\Client\Store\OrderController::class, 'markAsProcessing'])
            ->name('orders.mark-processing');
        Route::post('orders/{order}/mark-shipped', [App\Http\Controllers\Client\Store\OrderController::class, 'markAsShipped'])
            ->name('orders.mark-shipped');
        Route::post('orders/{order}/mark-delivered', [App\Http\Controllers\Client\Store\OrderController::class, 'markAsDelivered'])
            ->name('orders.mark-delivered');
        Route::post('orders/{order}/cancel', [App\Http\Controllers\Client\Store\OrderController::class, 'cancel'])
            ->name('orders.cancel');
        Route::get('orders/{order}/download-receipt', [App\Http\Controllers\Client\Store\OrderController::class, 'downloadReceipt'])
            ->name('orders.download-receipt');
        
        // Cuentas Bancarias
        Route::get('bank-accounts', [App\Http\Controllers\Client\Store\BankAccountController::class, 'index'])
            ->name('bank-accounts.index');
        Route::get('bank-accounts/create', [App\Http\Controllers\Client\Store\BankAccountController::class, 'create'])
            ->name('bank-accounts.create');
        Route::post('bank-accounts', [App\Http\Controllers\Client\Store\BankAccountController::class, 'store'])
            ->name('bank-accounts.store');
        Route::get('bank-accounts/{bankAccount}/edit', [App\Http\Controllers\Client\Store\BankAccountController::class, 'edit'])
            ->name('bank-accounts.edit');
        Route::put('bank-accounts/{bankAccount}', [App\Http\Controllers\Client\Store\BankAccountController::class, 'update'])
            ->name('bank-accounts.update');
        Route::delete('bank-accounts/{bankAccount}', [App\Http\Controllers\Client\Store\BankAccountController::class, 'destroy'])
            ->name('bank-accounts.destroy');
        Route::post('bank-accounts/{bankAccount}/set-default', [App\Http\Controllers\Client\Store\BankAccountController::class, 'setDefault'])
            ->name('bank-accounts.set-default');
    });
});

// API Pública de la Tienda (para frontend externo)
Route::prefix('api/store/{clientSlug}')->name('api.store.')->group(function () {
    // Catálogo
    Route::get('catalog', [App\Http\Controllers\Api\Store\StoreApiController::class, 'catalog'])
        ->name('catalog');
    Route::get('categories', [App\Http\Controllers\Api\Store\StoreApiController::class, 'categories'])
        ->name('categories');
    Route::get('product/{productSlug}', [App\Http\Controllers\Api\Store\StoreApiController::class, 'product'])
        ->name('product');
    
    // Órdenes
    Route::post('orders', [App\Http\Controllers\Api\Store\StoreApiController::class, 'createOrder'])
        ->name('orders.create');
    Route::get('orders/{orderId}', [App\Http\Controllers\Api\Store\StoreApiController::class, 'viewOrder'])
        ->name('orders.view');
    Route::post('orders/{orderId}/receipt', [App\Http\Controllers\Api\Store\StoreApiController::class, 'uploadReceipt'])
        ->name('orders.upload-receipt');
});
