<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('type', ['single', 'collection'])->default('collection');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_public_endpoint')->default(false);
            $table->string('endpoint_slug')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['client_id', 'slug']);
            $table->index(['client_id', 'is_visible']);
            $table->index(['client_id', 'is_public_endpoint']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
