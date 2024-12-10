<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Inserir o parâmetro do logo do sistema
        DB::table('parameters')->insert([
            'key' => 'system_logo',
            'name' => 'Logo do Sistema',
            'value' => null,
            'group' => 'system',
            'type' => 'image',
            'is_private' => false,
            'description' => 'Logo exibido na tela de login e cabeçalho do sistema',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('parameters')->where('key', 'system_logo')->delete();
    }
};
