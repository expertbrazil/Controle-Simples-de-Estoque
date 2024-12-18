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
        Schema::table('suppliers', function (Blueprint $table) {
            // Primeiro verifica se a coluna existe
            if (!Schema::hasColumn('suppliers', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            } else {
                // Se a coluna existe, modifica ela para garantir que está correta
                $table->timestamp('deleted_at')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
