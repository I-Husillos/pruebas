<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Treatment\StoreTreatmentRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Treatment\Application\Create\CreateTreatmentCommand;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Post(
        path: "/api/v1/treatments",
        tags: ["Treatments"],
        summary: "Crear Treatment",
        description: "Crear Treatment",
        operationId: "createTreatment",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreTreatmentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new CreateTreatmentCommand(
            $validated['name'],
            $validated['slug'],
            $validated['description'] ?? null,
            (bool) ($validated['published'] ?? false),
            $validated['available_markets'] ?? null,
            (int) ($validated['sort_order'] ?? 0),
            isset($validated['category_id']) ? (int) $validated['category_id'] : null,
            $validated['blocks_json'] ?? null
        ));

        return $this->sendResponse([], 'Tratamiento creado exitosamente', 201);
    }
}
