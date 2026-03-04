<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use OpenApi\Attributes as OA;
use Termosalud\Web\Page\Application\Search\SearchPagesByCriteriaQuery;

#[OA\Tag(
    name: "Content",
    description: "Endpoints para gestionar contenido: artículos y páginas"
)]
final class PagesGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/pages",
        tags: ["Content"],
        summary: "Listar páginas estáticas",
        operationId: "getPages",
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
        description: "Lista de páginas obtenida exitosamente"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\Page\Application\PagesResponse $response */
            $response = $this->queryBus->ask(new SearchPagesByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Pages retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving pages', ['error' => $e->getMessage()], 500);
        }
    }
}
