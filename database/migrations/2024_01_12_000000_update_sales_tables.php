<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Remover colunas antigas
            if (Schema::hasColumn('sales', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('sales', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
            if (Schema::hasColumn('sales', 'installments')) {
                $table->dropColumn('installments');
            }
            if (Schema::hasColumn('sales', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('sales', 'discount_type')) {
                $table->dropColumn('discount_type');
            }
            if (Schema::hasColumn('sales', 'discount_value')) {
                $table->dropColumn('discount_value');
            }
            if (Schema::hasColumn('sales', 'change_amount')) {
                $table->dropColumn('change_amount');
            }
            if (Schema::hasColumn('sales', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('sales', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('sales', 'payment_details')) {
                $table->dropColumn('payment_details');
            }
            if (Schema::hasColumn('sales', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }
            if (Schema::hasColumn('sales', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('sales', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            // Adicionar novas colunas
            if (!Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->after('customer_id');
            }
            if (!Schema::hasColumn('sales', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->after('subtotal_amount')->default(0);
            }
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->after('discount_percent')->default(0);
            }
            if (!Schema::hasColumn('sales', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('discount_amount')->default(0);
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method')->after('total_amount')->default('money');
            }
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->after('payment_method')->default('pending');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status')->after('payment_status')->default('completed');
            }
        });

        Schema::table('sale_items', function (Blueprint $table) {
            // Renomear colunas
            if (Schema::hasColumn('sale_items', 'unit_price') && !Schema::hasColumn('sale_items', 'price')) {
                $table->renameColumn('unit_price', 'price');
            }
            if (Schema::hasColumn('sale_items', 'total_price')) {
                $table->dropColumn('total_price');
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
                'total_amount',
                'payment_method',
                'payment_status',
                'status'
            ]);

            // Restore original columns
            $table->string('payment_method')->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->integer('installments')->nullable();
            $table->text('notes')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();
        });

        Schema::table('sale_items', function (Blueprint $table) {
            // Restaurar colunas antigas
            if (Schema::hasColumn('sale_items', 'price') && !Schema::hasColumn('sale_items', 'unit_price')) {
                $table->renameColumn('price', 'unit_price');
            }
            if (!Schema::hasColumn('sale_items', 'total_price')) {
                $table->decimal('total_price', 10, 2);
            }
        });
    }
};
