<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('zip_code', 9)->nullable()->after('state');
            $table->string('contact_name')->nullable()->after('zip_code');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp',
                'city',
                'state',
                'zip_code',
                'contact_name'
            ]);
        });
    }
};
