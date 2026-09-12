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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_published');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_published');
        });

        DB::table('products')->whereNotNull('badge')->update(['is_featured' => true]);
        DB::table('packages')->whereNotNull('badge')->update(['is_featured' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
