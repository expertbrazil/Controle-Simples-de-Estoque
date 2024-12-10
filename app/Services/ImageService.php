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
            // Usa o nome original do arquivo
            $fileName = $image->getClientOriginalName();
            
            // Carrega a imagem
            $img = $this->manager->read($image);
            
            // Redimensiona mantendo a proporção
            $img->cover(150, 150);
            
            // Define o caminho do diretório
            $publicPath = public_path("images/{$folder}");
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Salva o arquivo no formato original
            file_put_contents("{$publicPath}/{$fileName}", $img->encode());
            
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
