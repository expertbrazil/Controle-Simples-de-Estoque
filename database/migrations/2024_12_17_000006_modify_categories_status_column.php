<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyCategoriesStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionar coluna status se não existir
        if (!Schema::hasColumn('categories', 'status')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('status')->default(true);
            });
        }

        // Copiar dados de active para status se a coluna active existir
        if (Schema::hasColumn('categories', 'active')) {
            DB::statement('UPDATE categories SET status = active');
            
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'active')) {
                $table->boolean('active')->default(true);
            }
            
            if (Schema::hasColumn('categories', 'status')) {
                DB::statement('UPDATE categories SET active = status');
                $table->dropColumn('status');
            }
        });
    }
}
