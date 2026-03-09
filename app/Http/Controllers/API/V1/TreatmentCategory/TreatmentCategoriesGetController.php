<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Termosalud\Web\TreatmentCategory\Application\Search\SearchTreatmentCategoriesByCriteriaQuery;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoriesGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/treatment-categories",
        tags: ["Treatment Categories"],
        summary: "Listar TreatmentCategory",
        description: "Listar TreatmentCategory",
        operationId: "listTreatmentCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\TreatmentCategory\Application\TreatmentCategoriesResponse $response */
            $response = $this->queryBus->ask(new SearchTreatmentCategoriesByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'sort_order'),
                $request->query('order', 'asc'),
                (int) $request->query('limit', 100),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Treatment categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving treatment categories', ['error' => $e->getMessage()], 500);
        }
    }
}
