<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Treatment\StoreTreatmentRequest;
use App\Models\Treatment;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentPostController extends ApiController
{
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

        Treatment::create($validated);

        return $this->sendResponse([], 'Tratamiento creado exitosamente', 201);
    }
}
