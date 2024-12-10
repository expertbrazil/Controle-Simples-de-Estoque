<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text');
            $table->boolean('is_private')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Inserir configurações padrão do SMTP
        DB::table('parameters')->insert([
            [
                'key' => 'smtp_host',
                'name' => 'SMTP Host',
                'value' => 'smtp.gmail.com',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Servidor SMTP (ex: smtp.gmail.com)'
            ],
            [
                'key' => 'smtp_port',
                'name' => 'SMTP Porta',
                'value' => '587',
                'group' => 'email',
                'type' => 'number',
                'is_private' => false,
                'description' => 'Porta do servidor SMTP (ex: 587)'
            ],
            [
                'key' => 'smtp_username',
                'name' => 'SMTP Usuário',
                'value' => '',
                'group' => 'email',
                'type' => 'email',
                'is_private' => false,
                'description' => 'Email de usuário SMTP'
            ],
            [
                'key' => 'smtp_password',
                'name' => 'SMTP Senha',
                'value' => '',
                'group' => 'email',
                'type' => 'password',
                'is_private' => true,
                'description' => 'Senha do email (para Gmail, usar senha de app)'
            ],
            [
                'key' => 'smtp_encryption',
                'name' => 'SMTP Criptografia',
                'value' => 'tls',
                'group' => 'email',
                'type' => 'select',
                'is_private' => false,
                'description' => 'Tipo de criptografia (tls/ssl)'
            ],
            [
                'key' => 'mail_from_address',
                'name' => 'Email Remetente',
                'value' => '',
                'group' => 'email',
                'type' => 'email',
                'is_private' => false,
                'description' => 'Email que aparecerá como remetente'
            ],
            [
                'key' => 'mail_from_name',
                'name' => 'Nome Remetente',
                'value' => '',
                'group' => 'email',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Nome que aparecerá como remetente'
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('parameters');
    }
};
