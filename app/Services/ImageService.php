<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageService
{
    protected $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    
    public function store(UploadedFile $image, string $folder): string
    {
        try {
            // Gera um nome único para o arquivo
            $fileName = time() . '_' . uniqid() . '.webp';
            
            // Carrega a imagem
            $img = $this->manager->read($image);
            
            // Redimensiona mantendo a proporção
            $img->cover(150, 150);
            
            // Converte para WebP com qualidade 80
            $encodedImage = $img->toWebp(80);
            
            // Define o caminho completo
            $path = public_path('imagens/produtos');
            
            // Cria o diretório se não existir
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            // Salva o arquivo diretamente na pasta public
            file_put_contents($path . '/' . $fileName, $encodedImage);
            
            return $fileName;
        } catch (\Exception $e) {
            Log::error('Erro ao processar imagem: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function delete(?string $path, string $folder): void
    {
        if ($path) {
            $fullPath = public_path('imagens/produtos/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
