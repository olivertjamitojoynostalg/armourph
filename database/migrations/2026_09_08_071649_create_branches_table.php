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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->nullable()->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('hours')->nullable();
            $table->text('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $branches = DB::table('site_settings')->where('key', 'branches')->value('value');
        foreach (json_decode($branches ?: '[]', true) as $index => $branch) {
            DB::table('branches')->insert([
                'source_id' => $branch['source_id'] ?? null,
                'name' => $branch['name'],
                'address' => $branch['address'] ?? null,
                'contact' => $branch['contact'] ?? null,
                'hours' => $branch['hours'] ?? null,
                'url' => $branch['url'] ?? null,
                'sort_order' => $index + 1,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::table('site_settings')->where('key', 'branches')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $branches = DB::table('branches')->orderBy('sort_order')->get()->map(fn (object $branch): array => [
            'source_id' => $branch->source_id,
            'name' => $branch->name,
            'address' => $branch->address,
            'contact' => $branch->contact,
            'hours' => $branch->hours,
            'url' => $branch->url,
        ])->all();
        DB::table('site_settings')->updateOrInsert(['key' => 'branches'], ['value' => json_encode($branches), 'updated_at' => now(), 'created_at' => now()]);

        Schema::dropIfExists('branches');
    }
};
