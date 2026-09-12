<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('catalog_items')) {
            return;
        }

        DB::table('catalog_items')->where('kind', 'package')->orderBy('id')->each(function (object $item): void {
            DB::table('packages')->insert([
                'source_id' => $item->source_id,
                'name' => $item->name,
                'slug' => $item->slug,
                'category' => $item->category,
                'description' => $item->description,
                'specification' => $item->specification,
                'inclusions' => $item->inclusions,
                'price' => $item->price,
                'badge' => $item->badge,
                'sort_order' => $item->sort_order,
                'is_published' => $item->is_published,
                'image_path' => $item->image_path,
                'image_alt' => $item->image_alt,
                'is_sample_image' => $item->is_sample_image,
                'image_source' => $item->image_source,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        });

        DB::table('catalog_items')->where('kind', 'product')->orderBy('id')->each(function (object $item): void {
            $productTypeId = null;
            if ($item->category) {
                $productTypeId = DB::table('product_types')->where('name', $item->category)->value('id');
                $productTypeId ??= DB::table('product_types')->insertGetId([
                    'name' => $item->category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('products')->insert([
                'source_id' => $item->source_id,
                'product_type_id' => $productTypeId,
                'name' => $item->name,
                'slug' => $item->slug,
                'price' => $item->price,
                'sort_order' => $item->sort_order,
                'is_published' => $item->is_published,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        });

        Schema::drop('catalog_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The original combined table cannot be restored without losing normalized data.
    }
};
