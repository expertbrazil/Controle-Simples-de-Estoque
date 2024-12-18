<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Remover colunas antigas de preço que serão substituídas
            $table->dropColumn(['cost_price', 'price']);

            // Adicionar/Modificar colunas para corresponder ao formulário
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('barcode')->nullable();
            $table->decimal('last_purchase_price', 12, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('freight_cost', 12, 2)->default(0);
            $table->decimal('weight_kg', 8, 3)->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('consumer_markup', 5, 2)->default(0);
            $table->decimal('consumer_price', 12, 2)->default(0);
            $table->decimal('distributor_markup', 5, 2)->default(0);
            $table->decimal('distributor_price', 12, 2)->default(0);
            $table->integer('max_stock')->default(0);
            
            // Renomear image_path para image se ainda não foi feito
            if (Schema::hasColumn('products', 'image_path') && !Schema::hasColumn('products', 'image')) {
                $table->renameColumn('image_path', 'image');
            }

            // Renomear stock para stock_quantity se ainda não foi feito
            if (Schema::hasColumn('products', 'stock') && !Schema::hasColumn('products', 'stock_quantity')) {
                $table->renameColumn('stock', 'stock_quantity');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverter as alterações
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('price', 12, 2)->default(0);

            $table->dropForeign(['brand_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'brand_id',
                'supplier_id',
                'barcode',
                'last_purchase_price',
                'tax_percentage',
                'freight_cost',
                'weight_kg',
                'unit_cost',
                'consumer_markup',
                'consumer_price',
                'distributor_markup',
                'distributor_price',
                'max_stock'
            ]);

            // Reverter renomeações se necessário
            if (Schema::hasColumn('products', 'image') && !Schema::hasColumn('products', 'image_path')) {
                $table->renameColumn('image', 'image_path');
            }

            if (Schema::hasColumn('products', 'stock_quantity') && !Schema::hasColumn('products', 'stock')) {
                $table->renameColumn('stock_quantity', 'stock');
            }
        });
    }
};
