<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeepCheckProductImages extends Command
{
    protected $signature = 'products:deep-check-images';
    protected $description = 'Deeply check and diagnose product image paths';

    public function handle()
    {
        $products = Product::whereNotNull('image_path')->get();
        
        $this->info('Performing deep image path check...');
        
        foreach ($products as $product) {
            $this->line("\n--- Product ID: {$product->id} ---");
            $this->line("Current Image Path in DB: {$product->image_path}");
            
            // Verificações detalhadas
            $this->checkImagePath($product->image_path);
        }
    }
    
    private function checkImagePath($imagePath)
    {
        // Verifica caminhos possíveis
        $possiblePaths = [
            public_path($imagePath),
            public_path('storage/' . $imagePath),
            public_path('imagens/' . basename($imagePath)),
            public_path('imagens/produtos/' . basename($imagePath)),
            storage_path('app/public/' . $imagePath),
            storage_path('app/public/imagens/' . basename($imagePath))
        ];
        
        $this->line("Checking possible paths:");
        foreach ($possiblePaths as $path) {
            $exists = File::exists($path);
            $this->line("- {$path}: " . ($exists ? 'EXISTS' : 'Not found'));
        }
        
        // Verifica conteúdo do diretório público
        $this->line("\nContents of public/imagens:");
        $imensPaths = glob(public_path('imagens/*'));
        foreach ($imensPaths as $path) {
            $this->line("- " . basename($path));
        }
        
        $this->line("\nContents of public/images/produtos:");
        $imensProdutosPaths = glob(public_path('imagens/produtos/*'));
        foreach ($imensProdutosPaths as $path) {
            $this->line("- " . basename($path));
        }
    }
}
