<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

#[OA\Tag(
    name: "Media",
    description: "Endpoints para gestionar media"
)]
final class MediaPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/media",
        tags: ["Media"],
        summary: "Crear Media",
        description: "Crear Media",
        operationId: "createMedia",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $config = config('multimedia');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = $file->getMimeType();

            $isImage = in_array($mime, $config['allowed_image_types']);
            $isVideo = in_array($mime, $config['allowed_video_types']);

            if (! $isImage && ! $isVideo) {
                return $this->sendError('Tipo de archivo no permitido.', [], 422);
            }

            $maxSize = $isImage ? $config['max_image_size'] : $config['max_video_size'];
            if ($file->getSize() / 1024 > $maxSize) {
                return $this->sendError('El archivo excede el tamaño máximo permitido.', [], 422);
            }

            $path = $isImage ? $config['image_path'] : $config['video_path'];
            $storedPath = $file->store($path, 'public');

            if ($isImage) {
                try {
                    $fullPath = Storage::disk('public')->path($storedPath);
                    \Spatie\LaravelImageOptimizer\Facades\ImageOptimizer::optimize($fullPath);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Image optimization failed: ' . $e->getMessage());
                }
            }

            $url = Storage::url($storedPath);

            return $this->sendResponse([
                'url' => $url,
                'mime' => $mime,
                'type' => $isImage ? 'image' : 'video',
            ], 'Archivo subido exitosamente', 201);
        }

        return $this->sendError('No file uploaded', [], 400);
    }
}
