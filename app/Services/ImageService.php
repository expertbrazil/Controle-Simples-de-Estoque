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
            
            // Define o caminho do diretório
            $publicPath = public_path("images/{$folder}");
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Salva o arquivo
            file_put_contents("{$publicPath}/{$fileName}", $encodedImage);
            
            return $fileName;
        } catch (\Exception $e) {
            Log::error('Erro ao processar imagem: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function delete(?string $path, string $folder): void
    {
        if ($path) {
            // Remove do diretório public/images
            $publicPath = public_path("images/{$folder}/{$path}");
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
        }
    }
}
