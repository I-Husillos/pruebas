<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Treatment;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar treatments"
)]
final class TreatmentDeleteController extends ApiController
{
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
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();

        return $this->sendResponse([], 'Tratamiento eliminado exitosamente');
    }
}
