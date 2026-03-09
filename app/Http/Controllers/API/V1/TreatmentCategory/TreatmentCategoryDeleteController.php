<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\TreatmentCategory\Application\Delete\DeleteTreatmentCategoryCommand;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Delete(
        path: "/api/v1/treatment-categories/{id}",
        tags: ["Treatment Categories"],
        summary: "Eliminar TreatmentCategory",
        description: "Eliminar TreatmentCategory",
        operationId: "deleteTreatmentCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de TreatmentCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteTreatmentCategoryCommand($id));

        return $this->sendResponse([], 'Categoría de tratamiento eliminada exitosamente');
    }
}
