<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingFieldsToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('customers', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('customers', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'city',
                'state',
                'postal_code',
                'email',
                'notes'
            ]);
        });
    }
}