<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Modificar os campos de preço para suportar valores até 999.999.999,99
            $table->decimal('price', 12, 2)->change();
            $table->decimal('cost_price', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverter para a precisão original
            $table->decimal('price', 10, 2)->change();
            $table->decimal('cost_price', 10, 2)->nullable()->change();
        });
    }
};
