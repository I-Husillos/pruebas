<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ContentArticle;

#[OA\Tag(
    name: "Articles",
    description: "Endpoints para gestionar articles"
)]
final class ArticlePostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/articles",
        tags: ["Articles"],
        summary: "Crear Article",
        description: "Crear Article",
        operationId: "createArticle",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:blog,news,press',
            'title' => 'required|array',
            'title.es' => 'required|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'excerpt' => 'nullable|array',
            'content' => 'nullable|array',
            'published' => 'boolean',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:article_categories,id',
            'featured_image' => 'nullable|array',
        ]);

        ContentArticle::create($validated);

        return $this->sendResponse([], 'Artículo creado exitosamente', 201);
    }
}
