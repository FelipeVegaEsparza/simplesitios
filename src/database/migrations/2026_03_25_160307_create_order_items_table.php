<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            
            // Datos snapshot (por si el producto cambia después)
            $table->string('product_name');
            $table->string('variant_name')->nullable(); // Ej: "M / Azul"
            $table->string('sku')->nullable();
            $table->integer('price'); // Precio unitario en CLP
            $table->integer('quantity');
            $table->integer('subtotal'); // price * quantity
            
            $table->timestamps();
            
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
