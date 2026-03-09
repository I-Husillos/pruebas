<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Treatment\UpdateTreatmentRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Treatment\Application\Update\UpdateTreatmentCommand;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/treatments/{id}",
        tags: ["Treatments"],
        summary: "Actualizar Treatment",
        description: "Actualizar Treatment",
        operationId: "updateTreatment",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Treatment", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdateTreatmentRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateTreatmentCommand(
            $id,
            $validated['name'],
            $validated['slug'],
            $validated['description'] ?? null,
            (bool) ($validated['published'] ?? false),
            $validated['available_markets'] ?? null,
            (int) ($validated['sort_order'] ?? 0),
            isset($validated['category_id']) ? (int) $validated['category_id'] : null,
            $validated['blocks_json'] ?? null
        ));

        return $this->sendResponse([], 'Tratamiento actualizado exitosamente');
    }
}
