<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSalesTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop existing columns if they exist
            $columns = [
                'total', 'discount', 'final_amount', 'subtotal_amount',
                'discount_percent', 'discount_amount', 'total_amount'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('sales', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            // Add columns with correct structure
            $table->decimal('subtotal_amount', 10, 2)->after('user_id');
            $table->decimal('discount_percent', 5, 2)->default(0)->after('subtotal_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percent');
            $table->decimal('total_amount', 10, 2)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_amount',
                'discount_percent',
                'discount_amount',
                'total_amount'
            ]);
        });
    }
}
