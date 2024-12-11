<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['brand_id']);
            });
        } catch (\Exception $e) {
            // Foreign key não existe
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['supplier_id']);
            });
        } catch (\Exception $e) {
            // Foreign key não existe
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropColumn('brand_id');
            }
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropColumn('supplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here since we're just cleaning up
    }
};
