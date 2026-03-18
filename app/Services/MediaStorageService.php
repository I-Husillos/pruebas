<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class MediaStorageService
{
    public function store(UploadedFile $file, string $market): array
    {
        $config = config('multimedia');
        $mime = $file->getMimeType();
        $isImage = in_array($mime, $config['allowed_image_types']);
        
        // 1. Sanitizar el mercado (Security: Prevent Path Traversal) 🛡️
        $safeMarket = preg_replace('/[^a-z0-9\-]/', '', strtolower($market));
        
        // 2. Generar nombre único por contenido (Evita duplicados)
        $hash = md5_file($file->getRealPath());
        $extension = $file->getClientOriginalExtension();
        $fileName = "{$hash}.{$extension}";

        // 3. Construir ruta dinámica: uploads/{market}/{type}
        $subFolder = $isImage ? $config['folder_names']['images'] : $config['folder_names']['videos'];
        $relativeDir = "{$config['root_path']}/{$safeMarket}/{$subFolder}";
        $fullPath = "{$relativeDir}/{$fileName}";

        // 4. Guardar solo si no existe
        if (!Storage::disk('public')->exists($fullPath)) {
            $storedPath = $file->storeAs($relativeDir, $fileName, 'public');
            
            if ($isImage) {
                $this->optimize(Storage::disk('public')->path($storedPath));
            }
        }

        return [
            'url' => Storage::url($fullPath),
            'name' => $file->getClientOriginalName(),
            'type' => $isImage ? 'image' : 'video',
            'size' => $file->getSize(),
        ];
    }

    private function optimize(string $path): void
    {
        try {
            ImageOptimizer::optimize($path);
        } catch (\Exception $e) {
            Log::warning("Image optimization failed: " . $e->getMessage());
        }
    }
}