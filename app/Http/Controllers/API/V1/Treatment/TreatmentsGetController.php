<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Treatment\Application\Search\SearchTreatmentsByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar tratamientos médico estéticos"
)]
final class TreatmentsGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/treatments",
        tags: ["Treatments"],
        summary: "Listar tratamientos",
        operationId: "getTreatments",
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
        description: "Lista de tratamientos obtenida exitosamente"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\Treatment\Application\TreatmentsResponse $response */
            $response = $this->queryBus->ask(new SearchTreatmentsByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Treatments retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving treatments', ['error' => $e->getMessage()], 500);
        }
    }
}
