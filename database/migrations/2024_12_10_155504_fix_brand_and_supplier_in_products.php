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
        // Primeiro, vamos remover as chaves estrangeiras se existirem
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'products'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME IN ('products_brand_id_foreign', 'products_supplier_id_foreign')
        ");

        foreach ($foreignKeys as $foreignKey) {
            Schema::table('products', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        // Agora vamos remover as colunas se existirem
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropColumn('brand_id');
            }
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropColumn('supplier_id');
            }
        });

        // Finalmente, vamos adicionar as colunas novamente
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->after('category_id')->nullable()->constrained()->onDelete('restrict');
            }
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignId('supplier_id')->after('brand_id')->nullable()->constrained()->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['brand_id', 'supplier_id']);
        });
    }
};
