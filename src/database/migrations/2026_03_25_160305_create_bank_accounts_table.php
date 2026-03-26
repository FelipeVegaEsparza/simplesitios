<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('bank_name'); // Banco Estado, Banco de Chile, etc.
            $table->string('account_type'); // Cuenta Corriente, Cuenta Vista, etc.
            $table->string('account_number');
            $table->string('account_holder'); // Nombre del titular
            $table->string('rut')->nullable(); // RUT del titular
            $table->string('email')->nullable(); // Email para enviar comprobantes
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->text('instructions')->nullable(); // Instrucciones adicionales para el comprador
            $table->timestamps();
            
            $table->index(['client_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
