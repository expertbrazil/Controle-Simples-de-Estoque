<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Eletrônicos', 'status' => true],
            ['name' => 'Roupas', 'status' => true],
            ['name' => 'Acessórios', 'status' => true],
            ['name' => 'Calçados', 'status' => true],
            ['name' => 'Móveis', 'status' => true],
            ['name' => 'Decoração', 'status' => true],
            ['name' => 'Livros', 'status' => true],
            ['name' => 'Brinquedos', 'status' => true],
            ['name' => 'Esportes', 'status' => true],
            ['name' => 'Outros', 'status' => true]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
