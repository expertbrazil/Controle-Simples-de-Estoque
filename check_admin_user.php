<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$adminUser = User::where('email', 'admin@admin.com')->first();

if ($adminUser) {
    echo "Usuário Administrador encontrado:\n";
    echo "Nome: {$adminUser->name}\n";
    echo "Email: {$adminUser->email}\n";
    echo "Criado em: {$adminUser->created_at}\n";
} else {
    echo "Nenhum usuário administrador encontrado.\n";
}
