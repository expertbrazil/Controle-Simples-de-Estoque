<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class FixProductImages extends Command
{
    protected $signature = 'products:fix-images';
    protected $description = 'Fix product image paths and move files';

    public function handle()
    {
        $products = Product::whereNotNull('image_path')->get();
        
        foreach ($products as $product) {
            $fileName = basename($product->image_path);
            
            // Possíveis locais antigos de imagem
            $possibleOldPaths = [
                public_path('storage/' . $product->image_path),
                public_path('imagens/' . $fileName),
                public_path($product->image_path)
            ];
            
            $newPath = public_path('imagens/produtos/' . $fileName);
            
            // Tenta encontrar o arquivo em locais antigos
            foreach ($possibleOldPaths as $oldPath) {
                if (File::exists($oldPath)) {
                    // Cria o diretório de destino se não existir
                    File::ensureDirectoryExists(dirname($newPath));
                    
                    // Move o arquivo
                    File::move($oldPath, $newPath);
                    
                    $this->info("Moved: $oldPath -> $newPath");
                    break;
                }
            }
            
            // Atualiza o caminho no banco
            $product->image_path = 'imagens/produtos/' . $fileName;
            $product->save();
        }
        
        $this->info('Image path fix completed.');
    }
}
