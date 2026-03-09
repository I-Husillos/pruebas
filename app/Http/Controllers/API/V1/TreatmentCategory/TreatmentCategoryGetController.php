<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\TreatmentCategory\Application\Find\FindTreatmentCategoryByIdQuery;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/treatment-categories/{id}",
        tags: ["Treatment Categories"],
        summary: "Obtener TreatmentCategory por ID",
        description: "Obtener TreatmentCategory por ID",
        operationId: "getTreatmentCategoryById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de TreatmentCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    #[OA\Response(response: 404, description: "No encontrado")]
    public function __invoke(int $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\TreatmentCategory\Application\TreatmentCategoryResponse|null $category */
            $category = $this->queryBus->ask(new FindTreatmentCategoryByIdQuery($id));

            if (!$category) {
                return $this->sendError('Treatment category not found', [], 404);
            }

            return $this->sendResponse($category->toArray(), 'Treatment category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving treatment category', ['error' => $e->getMessage()], 500);
        }
    }
}
