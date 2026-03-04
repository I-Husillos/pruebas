<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/article-categories",
        tags: ["Article Categories"],
        summary: "Crear ArticleCategory",
        description: "Crear ArticleCategory",
        operationId: "createArticleCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'slug.en' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        ArticleCategory::create($validated);

        return $this->sendResponse([], 'Categoría de artículo creada exitosamente', 201);
    }
}
