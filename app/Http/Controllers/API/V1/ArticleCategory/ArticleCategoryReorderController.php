<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Reorder\ArticleCategory\ReorderArticleCategory;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/article-categories/reorder",
        tags: ["Article Categories"],
        summary: "Reordenar Categorías de Artículos",
        description: "Reordenar Categorías de Artículos mediante drag & drop",
        operationId: "reorderArticleCategories",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(ReorderArticleCategory $request): JsonResponse
    {
        $items = $request->validated()['items'];

        foreach ($items as $item) {
            ArticleCategory::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
