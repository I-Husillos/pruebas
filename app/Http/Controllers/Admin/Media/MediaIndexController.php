<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;use Illuminate\Http\Request;

final class MediaIndexController extends BaseController
{
public function __invoke(Request $request): Response
    {
        $config = config('multimedia');
        $root = $config['root_path']; // 'uploads'
        
        // Obtenemos todos los archivos dentro de 'uploads' recursivamente
        $allFiles = Storage::disk('public')->allFiles($root);

        $media = collect($allFiles)
            ->map(function ($path) use ($config) {
                // Determinamos el tipo basándonos en la carpeta (images o videos)
                $isImage = str_contains($path, '/' . $config['folder_names']['images'] . '/');
                $isVideo = str_contains($path, '/' . $config['folder_names']['videos'] . '/');

                if (!$isImage && !$isVideo) return null;

                return [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => basename($path),
                    'size' => Storage::disk('public')->size($path),
                    'modified' => Storage::disk('public')->lastModified($path),
                    'type' => $isImage ? 'image' : 'video',
                    // Extraemos el mercado de la ruta: uploads/{market}/...
                    'market' => explode('/', $path)[1] ?? 'unknown',
                ];
            })
            ->filter() // Eliminamos nulos
            ->sortByDesc('modified')
            ->values()
            ->all();

        return $this->render('Admin/Media/Index', [
            'media' => $media,
        ]);
    }
}
