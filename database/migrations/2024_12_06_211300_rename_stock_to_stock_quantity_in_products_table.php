<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock') && !Schema::hasColumn('products', 'stock_quantity')) {
                $table->renameColumn('stock', 'stock_quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock_quantity') && !Schema::hasColumn('products', 'stock')) {
                $table->renameColumn('stock_quantity', 'stock');
            }
        });
    }
};
