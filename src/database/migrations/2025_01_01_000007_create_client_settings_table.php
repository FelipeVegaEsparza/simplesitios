<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string')->comment('string, json, boolean, number');
            $table->string('group')->default('general');
            $table->timestamps();
            
            $table->unique(['client_id', 'key']);
            $table->index(['client_id', 'group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_settings');
    }
};
