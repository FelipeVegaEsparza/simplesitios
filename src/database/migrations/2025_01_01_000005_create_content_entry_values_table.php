<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_entry_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_field_id')->constrained()->onDelete('cascade');
            $table->longText('value')->nullable();
            $table->timestamps();
            
            $table->unique(['content_entry_id', 'section_field_id']);
            $table->index('section_field_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_entry_values');
    }
};
