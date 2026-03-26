<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('base_price'); // Precio en CLP (pesos chilenos)
            $table->string('sku')->nullable();
            $table->json('images')->nullable(); // Array de rutas de imágenes
            $table->boolean('has_variants')->default(false);
            $table->integer('total_stock')->default(0); // Suma del stock de variantes
            $table->boolean('track_stock')->default(true);
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['client_id', 'slug']);
            $table->index(['client_id', 'status']);
            $table->index(['client_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
