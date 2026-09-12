<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('specification')->nullable()->after('description');
            $table->string('badge')->nullable()->after('price');
            $table->string('image_path')->nullable()->after('is_published');
            $table->string('image_alt')->nullable()->after('image_path');
            $table->boolean('is_sample_image')->default(false)->after('image_alt');
            $table->text('image_source')->nullable()->after('is_sample_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'specification', 'badge', 'image_path', 'image_alt', 'is_sample_image', 'image_source']);
        });
    }
};
