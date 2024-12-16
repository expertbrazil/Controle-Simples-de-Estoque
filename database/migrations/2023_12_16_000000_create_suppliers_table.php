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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->char('tipo_pessoa', 1)->comment('F = Física, J = Jurídica');
            $table->string('documento', 18)->unique();
            $table->string('email')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('whatsapp', 15)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('rua')->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->char('uf', 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
