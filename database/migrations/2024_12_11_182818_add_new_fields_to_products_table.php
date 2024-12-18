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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'freight_cost')) {
                $table->decimal('freight_cost', 12, 2)->nullable()->after('cost_price');
            }
            if (!Schema::hasColumn('products', 'tax_percentage')) {
                $table->decimal('tax_percentage', 5, 2)->nullable()->after('freight_cost');
            }
            if (!Schema::hasColumn('products', 'consumer_markup')) {
                $table->decimal('consumer_markup', 5, 2)->nullable()->after('tax_percentage');
            }
            if (!Schema::hasColumn('products', 'distributor_markup')) {
                $table->decimal('distributor_markup', 5, 2)->nullable()->after('consumer_markup');
            }
            if (!Schema::hasColumn('products', 'consumer_price')) {
                $table->decimal('consumer_price', 12, 2)->nullable()->after('distributor_markup');
            }
            if (!Schema::hasColumn('products', 'distributor_price')) {
                $table->decimal('distributor_price', 12, 2)->nullable()->after('consumer_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'freight_cost',
                'tax_percentage',
                'consumer_markup',
                'distributor_markup',
                'consumer_price',
                'distributor_price'
            ]);
        });
    }
};
