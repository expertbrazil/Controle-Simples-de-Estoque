<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductImages extends Command
{
    protected $signature = 'products:check-images';
    protected $description = 'Check product image paths';

    public function handle()
    {
        $products = Product::whereNotNull('image_path')->get();
        
        $this->info('Product Image Paths:');
        foreach ($products as $product) {
            $this->line("Product ID: {$product->id}");
            $this->line("Image Path: {$product->image_path}");
            
            // Check if file exists
            $fullPath = public_path($product->image_path);
            $this->line("Full Path: {$fullPath}");
            $this->line("File Exists: " . (file_exists($fullPath) ? 'Yes' : 'No'));
            $this->line('---');
        }
    }
}
