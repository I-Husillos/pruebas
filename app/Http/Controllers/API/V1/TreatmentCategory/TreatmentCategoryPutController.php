<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\TreatmentCategory;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryPutController extends ApiController
{
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
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $category = TreatmentCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'slug.en' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $category->update($validated);

        return $this->sendResponse([], 'Categoría de tratamiento actualizada exitosamente');
    }
}
