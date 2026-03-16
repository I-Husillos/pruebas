<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Treatment\Application\Find\FindTreatmentQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar tratamientos médico estéticos"
)]
final class TreatmentGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/treatments/{id}",
        tags: ["Treatments"],
        summary: "Ver tratamiento por ID",
        operationId: "getTreatmentById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID del tratamiento",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(response: 200, description: "Tratamiento encontrado")]
    #[OA\Response(response: 404, description: "Tratamiento no encontrado")]
    public function __invoke(int $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\Treatment\Application\TreatmentResponse|null $treatment */
            $treatment = $this->queryBus->ask(new FindTreatmentQuery($id));

            if (! $treatment) {
                return $this->sendError('Treatment not found', [], 404);
            }

            return $this->sendResponse($treatment->toArray(), 'Treatment retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving treatment', ['error' => $e->getMessage()], 500);
        }
    }
}
