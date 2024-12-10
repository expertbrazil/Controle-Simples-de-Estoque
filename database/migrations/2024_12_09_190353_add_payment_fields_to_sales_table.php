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
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method')->default('money');
            } else {
                $table->string('payment_method')->default('money')->change();
            }
            if (!Schema::hasColumn('sales', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sales', 'change_amount')) {
                $table->decimal('change_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sales', 'installments')) {
                $table->integer('installments')->nullable();
            }
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (!Schema::hasColumn('sales', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }
            if (!Schema::hasColumn('sales', 'payment_details')) {
                $table->json('payment_details')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'paid_amount',
                'change_amount',
                'installments',
                'payment_status',
                'transaction_id',
                'payment_details'
            ]);
        });
    }
};
