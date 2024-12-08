<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->renameColumn('price', 'unit_price');
            $table->renameColumn('total', 'total_price');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->renameColumn('unit_price', 'price');
            $table->renameColumn('total_price', 'total');
        });
    }
};
