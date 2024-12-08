<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductStock extends Command
{
    protected $signature = 'product:check-stock {name}';
    protected $description = 'Check stock for a product by name';

    public function handle()
    {
        $name = $this->argument('name');
        $product = Product::where('name', $name)->first();
        
        if (!$product) {
            $this->error("Product not found: {$name}");
            return 1;
        }

        $this->info("Product: {$product->name}");
        $this->info("Stock: {$product->stock_quantity}");
        $this->info("SKU: {$product->sku}");
        $this->info("Price: {$product->price}");
        
        return 0;
    }
}
