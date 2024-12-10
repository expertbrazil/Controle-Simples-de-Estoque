<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSalesTableStructureAgain extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Remove the old columns if they exist
            if (Schema::hasColumn('sales', 'final_amount')) {
                $table->dropColumn('final_amount');
            }
            
            // Make sure we have the correct columns
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
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // No need to revert as this is a fix
        });
    }
}
