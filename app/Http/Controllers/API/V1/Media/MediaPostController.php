<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

#[OA\Tag(name: "Media", description: "Endpoints para gestionar media")]
final class MediaPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/media",
        tags: ["Media"],
        summary: "Crear Media",
        description: "Crear Media con soporte multimercado",
        operationId: "createMedia",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 422, description: "Error de validación")]
    public function __invoke(Request $request): JsonResponse
    {
        $config = config('multimedia');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = $file->getMimeType();

            // 1. Determinar tipo de archivo
            $isImage = in_array($mime, $config['allowed_image_types']);
            $isVideo = in_array($mime, $config['allowed_video_types']);

            if (! $isImage && ! $isVideo) {
                return $this->sendError('Tipo de archivo no permitido.', [], 422);
            }

            // 2. Validar tamaño
            $maxSize = $isImage ? $config['max_image_size'] : $config['max_video_size'];
            if ($file->getSize() / 1024 > $maxSize) {
                return $this->sendError("El archivo excede el tamaño máximo permitido. Máximo: {$maxSize} KB.", [], 422);
            }

            // --- LÓGICA MULTIMERCADO Y ANTI-DUPLICADOS ---
            
            // 3. Obtener y sanitizar el mercado (por seguridad)
            $market = preg_replace('/[^a-z0-9\-]/', '', strtolower($request->get('market', 'global')));

            // 4. Generar nombre basado en el contenido (Hash) para evitar duplicados
            $hash = md5_file($file->getRealPath());
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$hash}.{$extension}";

            // 5. Construir la ruta dinámica
            $subPath = $isImage ? $config['folder_names']['images'] : $config['folder_names']['videos'];
            $directory = "{$config['root_path']}/{$market}/{$subPath}";
            $finalRelativePath = "{$directory}/{$fileName}";

            // 6. Solo guardar y procesar si NO existe ya en el disco
            if (!Storage::disk('public')->exists($finalRelativePath)) {
                $storedPath = $file->storeAs($directory, $fileName, 'public');

                if ($isImage) {
                    try {
                        $fullPath = Storage::disk('public')->path($storedPath);
                        ImageOptimizer::optimize($fullPath);
                    } catch (\Exception $e) {
                        Log::warning('Image optimization failed: ' . $e->getMessage());
                    }
                }
            }

            // 7. Generar URL final (sea el nuevo o el que ya existía)
            $url = Storage::url($finalRelativePath);

            return $this->sendResponse([
                'url' => $url,
                'mime' => $mime,
                'type' => $isImage ? 'image' : 'video',
                'name' => $file->getClientOriginalName(), // Nombre original para el usuario
                'market' => $market
            ], 'Archivo procesado exitosamente', 201);
        }

        return $this->sendError('No se subió ningún archivo', [], 400);
    }
}