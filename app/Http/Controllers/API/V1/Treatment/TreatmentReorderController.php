<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Treatment;

use App\Http\Controllers\ApiController;
use App\Models\Treatment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Treatments",
    description: "Endpoints para gestionar tratamientos"
)]
final class TreatmentReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/treatments/reorder",
        tags: ["Treatments"],
        summary: "Reordenar Tratamientos",
        description: "Reordenar Tratamientos mediante drag & drop",
        operationId: "reorderTreatments",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:treatments,id',
            'items.*.order'  => 'required|integer|min:0',
        ])['items'];

        foreach ($items as $item) {
            Treatment::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
