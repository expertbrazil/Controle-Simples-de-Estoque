<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCategoriesActiveToStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('categories', 'status')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('status')->default(true);
            });

            // Se a coluna 'active' existir, copiar os dados para 'status'
            if (Schema::hasColumn('categories', 'active')) {
                DB::statement('UPDATE categories SET status = active');
                
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn('active');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('categories', 'status')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
