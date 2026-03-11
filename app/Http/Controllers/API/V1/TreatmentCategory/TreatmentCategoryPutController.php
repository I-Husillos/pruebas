<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\TreatmentCategory\UpdateTreatmentCategoryRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\TreatmentCategory\Application\Update\UpdateTreatmentCategoryCommand;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/treatment-categories/{id}",
        tags: ["Treatment Categories"],
        summary: "Actualizar TreatmentCategory",
        description: "Actualizar TreatmentCategory",
        operationId: "updateTreatmentCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de TreatmentCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdateTreatmentCategoryRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateTreatmentCategoryCommand(
            $id,
            $validated['status'],
            (int) ($validated['order'] ?? 0),
            $validated['translations'],
        ));

        return $this->sendResponse([], 'Categoría de tratamiento actualizada exitosamente');
    }
}
