<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Adiciona novas colunas se não existirem
            if (!Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->after('user_id');
            }
            if (!Schema::hasColumn('sales', 'discount_percent')) {
                $table->decimal('discount_percent', 10, 2)->default(0)->after('subtotal_amount');
            }
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percent');
            }
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_amount',
                'discount_percent',
                'discount_amount',
                'payment_status'
            ]);
        });
    }
};
