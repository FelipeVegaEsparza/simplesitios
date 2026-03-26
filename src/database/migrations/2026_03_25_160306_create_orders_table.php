<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_account_id')->nullable()->constrained()->onDelete('set null');
            
            // Datos del comprador (invitado)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('shipping_city')->nullable();
            $table->string('shipping_region')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->text('notes')->nullable(); // Notas del comprador
            
            // Totales (en CLP)
            $table->integer('subtotal');
            $table->integer('shipping_cost')->default(0);
            $table->integer('total_amount');
            
            // Estados
            $table->enum('status', [
                'pending',           // Pendiente de pago
                'payment_review',    // Comprobante subido, en revisión
                'paid',              // Pagado, preparando
                'processing',        // En preparación
                'shipped',           // Enviado
                'delivered',         // Entregado
                'cancelled',         // Cancelada
                'refunded'           // Reembolsado
            ])->default('pending');
            
            // Información de pago
            $table->enum('payment_method', ['transfer'])->default('transfer');
            $table->enum('payment_status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->string('transfer_receipt_path')->nullable(); // Ruta del comprobante
            $table->timestamp('transfer_date')->nullable();
            $table->text('payment_notes')->nullable();
            
            // Tracking
            $table->string('tracking_number')->nullable();
            $table->string('shipping_carrier')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Para cancelación automática
            
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index(['client_id', 'customer_email']);
            $table->index(['expires_at']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
