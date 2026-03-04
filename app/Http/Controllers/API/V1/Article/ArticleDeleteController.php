<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\ContentArticle;

#[OA\Tag(
    name: "Articles",
    description: "Endpoints para gestionar articles"
)]
final class ArticleDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/articles/{id}",
        tags: ["Articles"],
        summary: "Eliminar Article",
        description: "Eliminar Article",
        operationId: "deleteArticle",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Article", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $article = ContentArticle::findOrFail($id);
        $article->delete();

        return $this->sendResponse([], 'Artículo eliminado exitosamente');
    }
}
