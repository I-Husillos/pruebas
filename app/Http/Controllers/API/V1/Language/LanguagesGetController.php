<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Language;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Language\Application\Search\SearchLanguagesByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Languages",
    description: "Endpoints para gestionar languages"
)]
final class LanguagesGetController extends ApiController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {}

    #[OA\Get(
        path: "/api/v1/languages",
        tags: ["Languages"],
        summary: "Listar idiomas",
        description: "Obtiene la lista de todos los idiomas disponibles con soporte para búsqueda, filtros y paginación",
        operationId: "listLanguages",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "order_by",
        description: "Campo por el que ordenar",
        required: false,
        schema: new OA\Schema(type: "string", default: "name")
    )]
    #[OA\QueryParameter(
        name: "order",
        description: "Dirección del orden (asc, desc)",
        required: false,
        schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "asc")
    )]
    #[OA\QueryParameter(
        name: "limit",
        description: "Límite de resultados",
        required: false,
        schema: new OA\Schema(type: "integer", default: 5)
    )]
    #[OA\QueryParameter(
        name: "offset",
        description: "Desplazamiento para paginación",
        required: false,
        schema: new OA\Schema(type: "integer", default: 0)
    )]
    #[OA\Response(response: 200, description: "Lista de idiomas obtenida exitosamente")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\Language\Application\LanguagesResponse $response */
            $response = $this->queryBus->ask(new SearchLanguagesByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'name'),
                $request->query('order', 'asc'),
                (int) $request->query('limit', 5),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Languages retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving languages', ['error' => $e->getMessage()], 500);
        }
    }
}
