<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToSales extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method')->default('money');
            }
            if (!Schema::hasColumn('sales', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status')->default('completed');
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
        });
    }
}
