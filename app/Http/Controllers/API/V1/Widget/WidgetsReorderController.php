<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Widget;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Widget;

#[OA\Tag(
    name: "Widgets",
    description: "Endpoints para gestionar widgets"
)]
final class WidgetsReorderController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/widgets/reorder",
        tags: ["Widgets"],
        summary: "Reordenar Widget",
        description: "Reordenar Widget",
        operationId: "reorderWidget",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $widgets = $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|exists:widgets,id',
            'widgets.*.sort_order' => 'required|integer',
        ])['widgets'];

        foreach ($widgets as $widgetData) {
            Widget::where('id', $widgetData['id'])->update(['sort_order' => $widgetData['sort_order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
