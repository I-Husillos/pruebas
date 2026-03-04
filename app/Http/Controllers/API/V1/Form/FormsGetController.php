<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Form\Application\Find\SearchFormsByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar formularios de contacto y captación"
)]
final class FormsGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/forms",
        tags: ["Forms"],
        summary: "Listar formularios",
        operationId: "getForms",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "search",
        description: "Búsqueda por nombre",
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
        description: "Lista de formularios obtenida exitosamente"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Forms\Application\FormsResponse $response */
            $response = $this->queryBus->ask(new SearchFormsByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Forms retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving forms', ['error' => $e->getMessage()], 500);
        }
    }
}
