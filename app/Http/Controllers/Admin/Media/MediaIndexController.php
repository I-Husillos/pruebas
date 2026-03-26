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
        $root   = $config['root_path'];

        $allFiles = Storage::disk('public')->allFiles($root);

        $media = collect($allFiles)
            ->map(function ($path) use ($config) {
                $isImage = str_contains($path, '/' . $config['folder_names']['images'] . '/');
                $isVideo = str_contains($path, '/' . $config['folder_names']['videos'] . '/');

                if (!$isImage && !$isVideo) return null;

                return [
                    'path'     => $path,
                    'url'      => Storage::url($path),
                    'name'     => basename($path),
                    'size'     => Storage::disk('public')->size($path),
                    'modified' => Storage::disk('public')->lastModified($path),
                    'type'     => $isImage ? 'image' : 'video',
                    'market'   => explode('/', $path)[1] ?? 'unknown',
                ];
            })
            ->filter()
            // Agrupamos por nombre (hash) — todos los mercados del mismo archivo en un grupo
            ->groupBy('name')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'url'      => $first['url'],
                    'name'     => $first['name'],
                    'size'     => $first['size'],
                    'modified' => $first['modified'],
                    'type'     => $first['type'],
                    // Lista de todos los mercados donde está presente este archivo
                    'markets'  => $group->pluck('market')->unique()->sort()->values()->all(),
                ];
            })
            ->sortByDesc('modified')
            ->values()
            ->all();

        return $this->render('Admin/Media/Index', [
            'media' => $media,
        ]);
    }
}
