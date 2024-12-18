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
        // Primeiro, pega o nome da chave estrangeira
        $foreignKey = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'products'
            AND COLUMN_NAME = 'supplier_id'
            AND REFERENCED_TABLE_NAME = 'suppliers'
        ");

        if (!empty($foreignKey)) {
            Schema::table('products', function (Blueprint $table) use ($foreignKey) {
                // Remove a restrição antiga
                $table->dropForeign($foreignKey[0]->CONSTRAINT_NAME);
                
                // Adiciona a nova restrição com SET NULL
                $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primeiro, pega o nome da chave estrangeira
        $foreignKey = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'products'
            AND COLUMN_NAME = 'supplier_id'
            AND REFERENCED_TABLE_NAME = 'suppliers'
        ");

        if (!empty($foreignKey)) {
            Schema::table('products', function (Blueprint $table) use ($foreignKey) {
                // Remove a restrição SET NULL
                $table->dropForeign($foreignKey[0]->CONSTRAINT_NAME);
                
                // Restaura a restrição RESTRICT
                $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
                    ->onDelete('restrict');
            });
        }
    }
};
