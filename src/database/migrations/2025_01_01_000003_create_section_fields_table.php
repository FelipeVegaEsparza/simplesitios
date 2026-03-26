<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('label');
            $table->string('slug');
            $table->enum('type', [
                'text', 'textarea', 'richtext', 'number', 'boolean', 
                'date', 'datetime', 'email', 'url', 'image', 'file', 
                'select', 'json', 'repeater', 'gallery'
            ]);
            $table->boolean('is_required')->default(false);
            $table->text('default_value')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('show_in_list')->default(true);
            $table->boolean('show_in_api')->default(true);
            $table->string('column_width')->default('full')->comment('full, half, third');
            $table->json('options')->nullable()->comment('For select fields');
            $table->json('validation_rules')->nullable();
            $table->timestamps();
            
            $table->unique(['section_id', 'slug']);
            $table->index(['section_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_fields');
    }
};
