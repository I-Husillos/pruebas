<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use App\Models\TreatmentCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoryReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/treatment-categories/reorder",
        tags: ["Treatment Categories"],
        summary: "Reordenar Categorías de Tratamientos",
        description: "Reordenar Categorías de Tratamientos mediante drag & drop",
        operationId: "reorderTreatmentCategories",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:treatment_categories,id',
            'items.*.order'  => 'required|integer|min:0',
        ])['items'];

        foreach ($items as $item) {
            TreatmentCategory::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
