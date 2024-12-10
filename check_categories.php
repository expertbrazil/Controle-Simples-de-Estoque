<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;

$categories = Category::where('active', true)->get();

echo "Total de categorias ativas: " . $categories->count() . "\n";
echo "Detalhes das categorias:\n";
foreach ($categories as $category) {
    echo "ID: {$category->id}, Nome: {$category->name}, Ativo: {$category->active}, Parent ID: {$category->parent_id}\n";
}
