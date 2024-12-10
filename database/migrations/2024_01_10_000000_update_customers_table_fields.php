<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Remove colunas antigas
            $table->dropColumn([
                'document',
                'address',
                'city',
                'state',
                'postal_code',
                'notes'
            ]);

            // Adiciona novas colunas
            $table->string('cpf')->nullable()->after('phone');
            $table->string('cep', 9)->nullable()->after('cpf');
            $table->string('endereco')->nullable()->after('cep');
            $table->string('numero', 20)->nullable()->after('endereco');
            $table->string('bairro')->nullable()->after('numero');
            $table->string('cidade')->nullable()->after('bairro');
            $table->string('uf', 2)->nullable()->after('cidade');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Remove novas colunas
            $table->dropColumn([
                'cpf',
                'cep',
                'endereco',
                'numero',
                'bairro',
                'cidade',
                'uf'
            ]);

            // Restaura colunas antigas
            $table->string('document')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('notes')->nullable();
        });
    }
};
