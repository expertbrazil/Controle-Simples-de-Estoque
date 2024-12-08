<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class RecoverProductImages extends Command
{
    protected $signature = 'products:recover-images';
    protected $description = 'Recover product images by copying from storage';

    public function handle()
    {
        $products = Product::whereNotNull('image_path')->get();
        
        foreach ($products as $product) {
            $this->line("Processing Product ID: {$product->id}");
            
            // Nome do arquivo
            $fileName = basename($product->image_path);
            
            // Possíveis locais da imagem original
            $possiblePaths = [
                storage_path('app/private/public/products/' . $fileName),
                storage_path('app/public/products/' . $fileName),
                storage_path('app/public/' . $fileName),
                public_path('storage/products/' . $fileName),
                public_path('storage/' . $fileName),
            ];
            
            // Novo caminho para a imagem
            $newPath = public_path('imagens/produtos/' . $fileName);
            
            // Cria o diretório se não existir
            File::ensureDirectoryExists(dirname($newPath));
            
            // Procura a imagem nos possíveis locais
            foreach ($possiblePaths as $oldPath) {
                if (File::exists($oldPath)) {
                    $this->line("Found image at: {$oldPath}");
                    
                    // Copia a imagem para o novo local
                    File::copy($oldPath, $newPath);
                    
                    $this->line("Copied to: {$newPath}");
                    break;
                }
            }
            
            // Atualiza o caminho no banco
            $product->image_path = 'imagens/produtos/' . $fileName;
            $product->save();
        }
        
        $this->info('Image recovery completed.');
    }
}
