<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->string('accent')->nullable();
            $table->text('description')->nullable();
            $table->string('desktop_image_path');
            $table->string('mobile_image_path')->nullable();
            $table->string('image_alt');
            $table->string('button_label')->nullable();
            $table->string('button_url', 2000)->nullable();
            $table->string('content_position', 20)->default('left');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });

        $hero = json_decode(DB::table('site_settings')->where('key', 'hero')->value('value') ?: '[]', true);
        if (! empty($hero['banner_image_path'])) {
            DB::table('hero_slides')->insert([
                'eyebrow' => $hero['eyebrow'] ?? null,
                'title' => $hero['title'] ?? 'Upgrade your drive',
                'accent' => $hero['accent'] ?? null,
                'description' => $hero['description'] ?? null,
                'desktop_image_path' => $hero['banner_image_path'],
                'mobile_image_path' => $hero['banner_mobile_image_path'] ?? null,
                'image_alt' => $hero['image_alt'] ?? 'Armour automotive upgrades',
                'button_label' => 'Explore products',
                'button_url' => '/products',
                'content_position' => 'left',
                'sort_order' => 1,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
