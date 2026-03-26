<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('path');
            $table->string('disk')->default('local');
            $table->json('metadata')->nullable()->comment('dimensions, thumbnails, etc');
            $table->nullableMorphs('mediable');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['client_id', 'disk']);
            $table->index('mediable_type');
            $table->index('mediable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
