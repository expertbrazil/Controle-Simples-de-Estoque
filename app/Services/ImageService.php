<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageService
{
    protected $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(['driver' => 'gd']);
    }
    
    public function store(UploadedFile $image, string $folder): string
    {
        try {
            Log::info('Iniciando upload de imagem', [
                'folder' => $folder,
                'original_name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType()
            ]);

            // Usa o nome original do arquivo
            $fileName = uniqid() . '_' . $image->getClientOriginalName();
            Log::info('Nome do arquivo gerado', ['filename' => $fileName]);
            
            // Define o caminho do diretório
            $publicPath = public_path("images/{$folder}");
            Log::info('Caminho do diretório', ['path' => $publicPath]);

            // Cria o diretório se não existir
            if (!file_exists($publicPath)) {
                Log::info('Criando diretório', ['path' => $publicPath]);
                if (!mkdir($publicPath, 0755, true)) {
                    throw new \Exception("Não foi possível criar o diretório: {$publicPath}");
                }
            }
            
            // Carrega a imagem
            Log::info('Carregando imagem com Intervention');
            $img = $this->manager->make($image);
            
            // Redimensiona mantendo a proporção
            Log::info('Redimensionando imagem');
            $img->fit(150, 150);
            
            // Salva o arquivo no formato original
            $fullPath = "{$publicPath}/{$fileName}";
            Log::info('Salvando imagem', ['path' => $fullPath]);
            $img->save($fullPath);
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Arquivo não foi salvo corretamente: {$fullPath}");
            }
            
            Log::info('Imagem salva com sucesso', ['path' => $fullPath]);
            return $fileName;
        } catch (\Exception $e) {
            Log::error('Erro ao processar imagem', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    public function delete(?string $path, string $folder): void
    {
        if ($path) {
            $fullPath = public_path("images/{$folder}/{$path}");
            Log::info('Tentando deletar arquivo', ['path' => $fullPath]);
            
            if (file_exists($fullPath)) {
                Log::info('Arquivo encontrado, deletando');
                if (!unlink($fullPath)) {
                    Log::error('Não foi possível deletar o arquivo', ['path' => $fullPath]);
                    throw new \Exception("Não foi possível deletar o arquivo: {$fullPath}");
                }
                Log::info('Arquivo deletado com sucesso');
            } else {
                Log::warning('Arquivo não encontrado para deletar', ['path' => $fullPath]);
            }
        }
    }
}
