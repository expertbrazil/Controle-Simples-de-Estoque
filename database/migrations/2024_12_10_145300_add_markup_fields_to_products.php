<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('tax_percentage', 5, 2)->default(0)->after('cost_price'); // Percentual de impostos
            $table->decimal('freight_cost', 10, 2)->default(0)->after('tax_percentage'); // Custo do frete
            $table->decimal('weight_kg', 8, 3)->default(0)->after('freight_cost'); // Peso em kg
            $table->decimal('unit_cost', 10, 2)->default(0)->after('weight_kg'); // Custo por unidade (calculado)
            $table->decimal('markup_percentage', 5, 2)->default(0)->after('unit_cost'); // Percentual de markup
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'tax_percentage',
                'freight_cost',
                'weight_kg',
                'unit_cost',
                'markup_percentage'
            ]);
        });
    }
};
