<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->decimal('purchase_price', 10, 2)->comment('Preço de compra');
            $table->decimal('cost_price', 10, 2)->comment('Preço de custo');
            $table->integer('quantity')->comment('Quantidade');
            $table->text('notes')->nullable()->comment('Observações');
            $table->foreignId('user_id')->constrained()->comment('Usuário que registrou');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_entries');
    }
};
