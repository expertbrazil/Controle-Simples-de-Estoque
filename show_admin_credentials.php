<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$adminUser = User::where('email', 'admin@admin.com')->first();

if ($adminUser) {
    echo "Credenciais do Usuário Administrador:\n";
    echo "Nome: {$adminUser->name}\n";
    echo "Email: {$adminUser->email}\n";
    echo "Senha padrão: admin123\n";
} else {
    echo "Nenhum usuário administrador encontrado.\n";
}
