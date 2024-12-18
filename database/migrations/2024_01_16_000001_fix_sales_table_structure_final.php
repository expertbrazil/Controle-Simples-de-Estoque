<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSalesTableStructureFinal extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraint
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Drop and recreate the user_id column
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->foreignId('user_id')->after('supplier_id')->constrained();
        });

        // Add or update the remaining columns
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->after('user_id')->default(0);
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
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // No need to revert as this is a fix
        });
    }
}
