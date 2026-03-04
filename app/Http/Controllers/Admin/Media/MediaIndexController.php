<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

final class MediaIndexController extends Controller
{
    public function __invoke(): Response
    {
        $config = config('multimedia');

        $images = collect(Storage::disk('public')->files($config['image_path']))
            ->map(function ($path) {
                return [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => basename($path),
                    'size' => Storage::disk('public')->size($path),
                    'modified' => Storage::disk('public')->lastModified($path),
                    'type' => 'image',
                ];
            });

        $videos = collect(Storage::disk('public')->files($config['video_path']))
            ->map(function ($path) {
                return [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'name' => basename($path),
                    'size' => Storage::disk('public')->size($path),
                    'modified' => Storage::disk('public')->lastModified($path),
                    'type' => 'video',
                ];
            });

        $media = $images->merge($videos)
            ->sortByDesc('modified')
            ->values()
            ->all();

        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
        ]);
    }
}
