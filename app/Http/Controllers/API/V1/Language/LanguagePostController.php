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
final class LanguagePostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/languages",
        tags: ["Languages"],
        summary: "Crear Language",
        description: "Crear Language",
        operationId: "createLanguage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:2|unique:languages,code',
            'name' => 'required|string|max:255',
            'native_name' => 'required|string|max:255',
            'direction' => 'required|in:ltr,rtl',
            'active' => 'boolean',
            'fallback_language' => 'nullable|string|max:2',
        ]);

        Language::create($validated);

        return $this->sendResponse([], 'Idioma creado exitosamente', 201);
    }
}
