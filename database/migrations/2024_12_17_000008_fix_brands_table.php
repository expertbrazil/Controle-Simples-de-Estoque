<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('brands', function (Blueprint $table) {
                if (!Schema::hasColumn('brands', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('brands', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('brands', 'status')) {
                    $table->boolean('status')->default(true);
                }
                if (!Schema::hasColumn('brands', 'deleted_at')) {
                    $table->softDeletes();
                }
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
        Schema::dropIfExists('brands');
    }
}
