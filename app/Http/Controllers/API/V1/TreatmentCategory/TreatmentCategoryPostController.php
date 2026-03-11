<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\TreatmentCategory\StoreTreatmentCategoryRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\TreatmentCategory\Application\Create\CreateTreatmentCategoryCommand;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Post(
        path: "/api/v1/treatment-categories",
        tags: ["Treatment Categories"],
        summary: "Crear TreatmentCategory",
        description: "Crear TreatmentCategory",
        operationId: "createTreatmentCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreTreatmentCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new CreateTreatmentCategoryCommand(
            $validated['status'],
            (int) ($validated['order'] ?? 0),
            $validated['translations'],
        ));

        return $this->sendResponse([], 'Categoría de tratamiento creada exitosamente', 201);
    }
}
