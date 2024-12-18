<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->integer('min_stock')->default(0);
            }
            if (!Schema::hasColumn('products', 'last_purchase_price')) {
                $table->decimal('last_purchase_price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'tax_percentage')) {
                $table->decimal('tax_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'freight_cost')) {
                $table->decimal('freight_cost', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'weight_kg')) {
                $table->decimal('weight_kg', 8, 3)->default(0);
            }
            if (!Schema::hasColumn('products', 'unit_cost')) {
                $table->decimal('unit_cost', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'consumer_markup')) {
                $table->decimal('consumer_markup', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'consumer_price')) {
                $table->decimal('consumer_price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'distributor_markup')) {
                $table->decimal('distributor_markup', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'distributor_price')) {
                $table->decimal('distributor_price', 10, 2)->default(0);
            }
            // Rename cost_price and price columns if they exist
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop all the new columns
            $table->dropColumn([
                'barcode',
                'brand_id',
                'supplier_id',
                'min_stock',
                'last_purchase_price',
                'tax_percentage',
                'freight_cost',
                'weight_kg',
                'unit_cost',
                'consumer_markup',
                'consumer_price',
                'distributor_markup',
                'distributor_price'
            ]);
            // Restore original columns
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
        });
    }
};
