<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

#[OA\Tag(
    name: "Media",
    description: "Endpoints para gestionar media"
)]
final class MediaDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/media",
        tags: ["Media"],
        summary: "Borrar Media",
        description: "Eliminar Media",
        operationId: "deleteMedia",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "url",
        description: "URL del archivo a eliminar",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $url = $request->query('url');
        if (! $url) {
            return $this->sendError('URL es requerida', [], 422);
        }

        $rawPath = parse_url((string) $url, PHP_URL_PATH);
        if (! is_string($rawPath) || ! str_starts_with($rawPath, '/storage/')) {
            return $this->sendError('URL inválida.', [], 422);
        }

        $path = ltrim(substr($rawPath, strlen('/storage/')), '/');
        if ($path === '' || str_contains($path, '..')) {
            return $this->sendError('Ruta inválida.', [], 422);
        }

        $config = config('multimedia');
        $imagePrefix = trim((string) ($config['image_path'] ?? 'uploads/images'), '/');
        $videoPrefix = trim((string) ($config['video_path'] ?? 'uploads/videos'), '/');

        $isAllowedPath = str_starts_with($path, $imagePrefix . '/')
            || str_starts_with($path, $videoPrefix . '/');

        if (! $isAllowedPath) {
            return $this->sendError('No autorizado para eliminar este archivo.', [], 403);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return $this->sendResponse([], 'Archivo eliminado exitosamente');
        }

        return $this->sendError('Archivo no encontrado', [], 404);
    }
}
