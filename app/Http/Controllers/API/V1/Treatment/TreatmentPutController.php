<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Treatment\UpdateTreatmentRequest;
use App\Models\Treatment;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentPutController extends ApiController
{
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
        $treatment = Treatment::findOrFail($id);
        $validated = $request->validated();

        $treatment->update($validated);

        return $this->sendResponse([], 'Tratamiento actualizado exitosamente');
    }
}
