<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\ArticleCategory;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/article-categories/{id}",
        tags: ["Article Categories"],
        summary: "Eliminar ArticleCategory",
        description: "Eliminar ArticleCategory",
        operationId: "deleteArticleCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ArticleCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $category = ArticleCategory::findOrFail($id);
        $category->delete();

        return $this->sendResponse([], 'Categoría de artículo eliminada exitosamente');
    }
}
