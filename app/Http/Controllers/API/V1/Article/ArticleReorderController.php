<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use App\Models\ContentArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Articles",
    description: "Endpoints para gestionar articles"
)]
final class ArticleReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/articles/reorder",
        tags: ["Articles"],
        summary: "Reordenar Artículos",
        description: "Reordenar Artículos mediante drag & drop",
        operationId: "reorderArticles",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:articles,id',
            'items.*.order'  => 'required|integer|min:0',
        ])['items'];

        foreach ($items as $item) {
            ContentArticle::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
