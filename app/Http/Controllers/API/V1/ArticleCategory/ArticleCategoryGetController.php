<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ArticleCategory\Application\Find\FindArticleCategoryByIdQuery;

#[OA\Tag(
    name: "Content",
    description: "Endpoints para gestionar contenido: artículos y páginas"
)]
final class ArticleCategoryGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/article-categories/{id}",
        tags: ["Content"],
        summary: "Ver categoría de artículo por ID",
        operationId: "getArticleCategoryById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID de la categoría de artículo",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Categoría de artículo encontrada"
    )]
    #[OA\Response(
        response: 404,
        description: "Categoría de artículo no encontrada"
    )]
    public function __invoke(int $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\ArticleCategory\Application\ArticleCategoryResponse|null $category */
            $category = $this->queryBus->ask(new FindArticleCategoryByIdQuery($id));

            if (!$category) {
                return $this->sendError('Article category not found', [], 404);
            }

            return $this->sendResponse($category->toArray(), 'Article category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving article category', ['error' => $e->getMessage()], 500);
        }
    }
}
