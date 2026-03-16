<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\Treatment\Application\Delete\DeleteTreatmentLocalizationCommand;

final class TreatmentLocalizationDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Delete(
        path: "/api/v1/treatments/localizations/{localizationId}",
        tags: ["Treatments"],
        summary: "Eliminar localización de un tratamiento",
        operationId: "deleteTreatmentLocalization",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "localizationId",
        description: "ID de la localización",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(response: 200, description: "Localización eliminada")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $localizationId): JsonResponse
    {
        $this->commandBus->dispatch(
            new DeleteTreatmentLocalizationCommand($localizationId)
        );

        return $this->sendResponse([], 'Localización eliminada exitosamente');
    }
}
