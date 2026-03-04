<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\TreatmentCategory;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryDeleteController extends ApiController
{
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
        $category = TreatmentCategory::findOrFail($id);
        $category->delete();

        return $this->sendResponse([], 'Categoría de tratamiento eliminada exitosamente');
    }
}
