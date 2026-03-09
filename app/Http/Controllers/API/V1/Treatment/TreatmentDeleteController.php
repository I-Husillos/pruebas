<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Treatment\Application\Delete\DeleteTreatmentCommand;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Delete(
        path: "/api/v1/treatments/{id}",
        tags: ["Treatments"],
        summary: "Eliminar Treatment",
        description: "Eliminar Treatment",
        operationId: "deleteTreatment",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Treatment", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteTreatmentCommand($id));

        return $this->sendResponse([], 'Tratamiento eliminado exitosamente');
    }
}
