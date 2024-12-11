<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Renomeando campos existentes
            $table->renameColumn('price', 'retail_price');
            $table->renameColumn('markup_percentage', 'retail_markup');
            
            // Adicionando novos campos
            $table->decimal('consumer_markup', 5, 2)->default(0)->after('retail_markup');
            $table->decimal('consumer_price', 10, 2)->default(0)->after('consumer_markup');
            $table->decimal('distributor_markup', 5, 2)->default(0)->after('consumer_price');
            $table->decimal('distributor_price', 10, 2)->default(0)->after('distributor_markup');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Revertendo campos renomeados
            $table->renameColumn('retail_price', 'price');
            $table->renameColumn('retail_markup', 'markup_percentage');
            
            // Removendo novos campos
            $table->dropColumn([
                'consumer_markup',
                'consumer_price',
                'distributor_markup',
                'distributor_price'
            ]);
        });
    }
};
