<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ListProducts extends Command
{
    protected $signature = 'products:list';
    protected $description = 'Lista todos os produtos cadastrados';

    public function handle()
    {
        $products = Product::all();
        
        $this->info("\nProdutos cadastrados:\n");
        
        foreach ($products as $product) {
            $this->line(sprintf(
                "ID: %d | Nome: %s | SKU: %s | Preço: R$ %.2f | Estoque: %d",
                $product->id,
                $product->name,
                $product->sku,
                $product->price,
                $product->stock_quantity
            ));
        }
        
        $this->info("\nTotal de produtos: " . $products->count());
    }
}
