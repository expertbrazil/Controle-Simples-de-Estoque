<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBrandsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            // Primeiro, vamos verificar se a coluna 'active' existe
            if (Schema::hasColumn('brands', 'active')) {
                // Adicionar nova coluna status
                $table->boolean('status')->default(true)->after('description');
                
                // Copiar dados de active para status
                DB::statement('UPDATE brands SET status = active');
                
                // Remover coluna active
                $table->dropColumn('active');
            } else if (!Schema::hasColumn('brands', 'status')) {
                // Se não existe nem active nem status, criar status
                $table->boolean('status')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('brands', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }
}
