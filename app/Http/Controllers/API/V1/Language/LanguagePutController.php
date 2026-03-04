<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Language;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Language;

#[OA\Tag(
    name: "Languages",
    description: "Endpoints para gestionar languages"
)]
final class LanguagePutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/languages/{id}",
        tags: ["Languages"],
        summary: "Actualizar Language",
        description: "Actualizar Language",
        operationId: "updateLanguage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Language", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'native_name' => 'required|string|max:255',
            'direction' => 'required|in:ltr,rtl',
            'active' => 'boolean',
            'fallback_language' => 'nullable|string|max:2',
        ]);

        $language->update($validated);

        return $this->sendResponse([], 'Idioma actualizado exitosamente');
    }
}
