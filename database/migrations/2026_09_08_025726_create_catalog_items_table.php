<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20)->index();
            $table->unsignedInteger('source_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description');
            $table->string('specification')->nullable();
            $table->json('inclusions')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('badge')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->boolean('is_sample_image')->default(false);
            $table->text('image_source')->nullable();
            $table->timestamps();
            $table->unique(['kind', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_items');
    }
};
