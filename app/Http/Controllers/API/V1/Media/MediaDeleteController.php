<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class MediaDeleteController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $url = $request->query('url');
        if (!$url) return $this->sendError('URL es requerida', [], 422);

        // 1. Extraer la ruta relativa del storage
        $rawPath = parse_url((string) $url, PHP_URL_PATH);
        if (!is_string($rawPath) || !str_contains($rawPath, '/storage/')) {
            return $this->sendError('URL inválida.', [], 422);
        }

        // Convertimos /storage/uploads/es-es/images/hash.jpg -> uploads/es-es/images/hash.jpg
        $path = ltrim(explode('/storage/', $rawPath)[1], '/');

        // 2. Validación de Seguridad (Whitelist de carpeta raíz)
        $config = config('multimedia');
        if (!str_starts_with($path, $config['root_path'] . '/')) {
            return $this->sendError('No autorizado para eliminar fuera de la carpeta media.', [], 403);
        }

        // 3. Ejecutar eliminación
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return $this->sendResponse([], 'Archivo eliminado exitosamente');
        }

        return $this->sendError('Archivo no encontrado', [], 404);
    }
}