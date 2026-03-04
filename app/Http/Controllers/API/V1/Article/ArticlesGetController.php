<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use OpenApi\Attributes as OA;
use Termosalud\Web\Article\Application\Search\SearchArticlesByCriteriaQuery;

#[OA\Tag(
    name: "Content",
    description: "Endpoints para gestionar contenido: artículos y páginas"
)]
final class ArticlesGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/articles",
        tags: ["Content"],
        summary: "Listar artículos del blog",
        operationId: "getArticles",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "search",
        description: "Búsqueda por título",
        required: false,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\QueryParameter(
        name: "order_by",
        description: "Campo por el que ordenar",
        required: false,
        schema: new OA\Schema(type: "string", default: "id")
    )]
    #[OA\QueryParameter(
        name: "order",
        description: "Dirección del orden (asc, desc)",
        required: false,
        schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "desc")
    )]
    #[OA\QueryParameter(
        name: "limit",
        description: "Límite de resultados",
        required: false,
        schema: new OA\Schema(type: "integer", default: 15)
    )]
    #[OA\QueryParameter(
        name: "offset",
        description: "Desplazamiento para paginación",
        required: false,
        schema: new OA\Schema(type: "integer", default: 0)
    )]
    #[OA\Response(
        response: 200,
        description: "Lista de artículos obtenida exitosamente"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Content\Application\ArticlesResponse $response */
            $response = $this->queryBus->ask(new SearchArticlesByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'published_at'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 100),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Articles retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving articles', ['error' => $e->getMessage()], 500);
        }
    }
}
