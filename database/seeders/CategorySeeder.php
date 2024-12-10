<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Eletrônicos', 'active' => true],
            ['name' => 'Informática', 'parent_name' => 'Eletrônicos'],
            ['name' => 'Smartphones', 'parent_name' => 'Eletrônicos'],
            
            ['name' => 'Roupas', 'active' => true],
            ['name' => 'Masculino', 'parent_name' => 'Roupas'],
            ['name' => 'Feminino', 'parent_name' => 'Roupas'],
            
            ['name' => 'Alimentos', 'active' => true],
            ['name' => 'Bebidas', 'parent_name' => 'Alimentos'],
            ['name' => 'Congelados', 'parent_name' => 'Alimentos']
        ];

        foreach ($categories as $categoryData) {
            $parentName = $categoryData['parent_name'] ?? null;
            unset($categoryData['parent_name']);

            $category = Category::create($categoryData);

            if ($parentName) {
                $parentCategory = Category::where('name', $parentName)->first();
                if ($parentCategory) {
                    $category->parent_id = $parentCategory->id;
                    $category->save();
                }
            }
        }
    }
}
