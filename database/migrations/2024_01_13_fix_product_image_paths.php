<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    public function up()
    {
        $products = Product::whereNotNull('image_path')->get();
        
        foreach ($products as $product) {
            // Extrai o nome do arquivo do caminho atual
            $fileName = basename($product->image_path);
            
            // Move o arquivo fisicamente se existir
            $oldPath = public_path($product->image_path);
            $newPath = public_path('imagens/produtos/' . $fileName);
            
            if (file_exists($oldPath)) {
                // Cria o diretório se não existir
                if (!file_exists(public_path('imagens/produtos'))) {
                    mkdir(public_path('imagens/produtos'), 0755, true);
                }
                
                rename($oldPath, $newPath);
            }
            
            // Atualiza o caminho no banco
            $product->image_path = 'imagens/produtos/' . $fileName;
            $product->save();
        }
    }

    public function down()
    {
        // Não é necessário fazer rollback desta migration
    }
};
