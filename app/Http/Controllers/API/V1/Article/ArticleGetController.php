<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Article\Application\Find\FindArticleByIdQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Content",
    description: "Endpoints para gestionar contenido: artículos y páginas"
)]
final class ArticleGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/articles/{id}",
        tags: ["Content"],
        summary: "Ver artículo por ID",
        operationId: "getArticleById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID del artículo",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Artículo encontrado"
    )]
    #[OA\Response(
        response: 404,
        description: "Artículo no encontrado"
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Content\Application\ArticleResponse|null $article */
            $article = $this->queryBus->ask(new FindArticleByIdQuery((int) $id));

            if (!$article) {
                return $this->sendError('Article not found', [], 404);
            }

            return $this->sendResponse($article->toArray(), 'Article retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving article', ['error' => $e->getMessage()], 500);
        }
    }
}
