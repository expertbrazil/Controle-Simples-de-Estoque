<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $brands = [
            ['name' => 'Nike', 'status' => true],
            ['name' => 'Adidas', 'status' => true],
            ['name' => 'Puma', 'status' => true],
            ['name' => 'Reebok', 'status' => true],
            ['name' => 'Under Armour', 'status' => true],
            ['name' => 'Asics', 'status' => true],
            ['name' => 'New Balance', 'status' => true],
            ['name' => 'Oakley', 'status' => true],
            ['name' => 'Mizuno', 'status' => true],
            ['name' => 'Fila', 'status' => true]
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }
    }
}
