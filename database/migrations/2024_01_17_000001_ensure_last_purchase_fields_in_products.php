<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'last_purchase_price')) {
                $table->decimal('last_purchase_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'last_purchase_date')) {
                $table->timestamp('last_purchase_date')->nullable()->after('last_purchase_price');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['last_purchase_price', 'last_purchase_date']);
        });
    }
};
