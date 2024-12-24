<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Alvejante Sem Cloro 5L',
                'sku' => 'ALV5L',
                'barcode' => '7894900011518',
                'consumer_price' => 19.99,
                'stock_quantity' => 30,
                'status' => true,
                'image' => 'alvejante_sem_cloro_5L_ok_Correto.webp'
            ],
            [
                'name' => 'Amaciante Algodão Ternura 5L',
                'sku' => 'AMA5L',
                'barcode' => '7893500099913',
                'consumer_price' => 24.90,
                'stock_quantity' => 25,
                'status' => true,
                'image' => 'amaciante_algodao_ternura_5L.webp'
            ],
            [
                'name' => 'Feijão Carioca 1kg',
                'sku' => 'FEIJ1KG',
                'barcode' => '7891234567890',
                'consumer_price' => 8.99,
                'stock_quantity' => 40,
                'status' => true
            ],
            [
                'name' => 'Café Pilão 500g',
                'sku' => 'CAFE500',
                'barcode' => '7892222222222',
                'consumer_price' => 15.90,
                'stock_quantity' => 25,
                'status' => true
            ],
            [
                'name' => 'Leite Integral 1L',
                'sku' => 'LEITE1L',
                'barcode' => '7893333333333',
                'consumer_price' => 4.99,
                'stock_quantity' => 60,
                'status' => true
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
