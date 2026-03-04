<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Language;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Language;

#[OA\Tag(
    name: "Languages",
    description: "Endpoints para gestionar languages"
)]
final class LanguageDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/languages/{id}",
        tags: ["Languages"],
        summary: "Eliminar Language",
        description: "Eliminar Language",
        operationId: "deleteLanguage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Language", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return $this->sendResponse([], 'Idioma eliminado exitosamente');
    }
}
