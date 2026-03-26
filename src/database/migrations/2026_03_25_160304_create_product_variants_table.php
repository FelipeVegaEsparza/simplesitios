<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variant_name'); // Ej: "M / Azul"
            $table->string('sku')->nullable();
            $table->integer('price_override')->nullable(); // Precio diferente al base, null = usar base_price
            $table->integer('stock')->default(0);
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
