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
final class WidgetPutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/widgets/{id}",
        tags: ["Widgets"],
        summary: "Actualizar Widget",
        description: "Actualizar Widget",
        operationId: "updateWidget",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Widget", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $widget = Widget::findOrFail($id);

        $validated = $request->validate([
            'zone_id' => 'required|exists:widget_zones,id',
            'type' => 'required|in:menu,form,wysiwyg,fixed_content',
            'title' => 'nullable|array',
            'title.es' => 'nullable|string|max:255',
            'title.en' => 'nullable|string|max:255',
            'config' => 'required|array',
            'sort_order' => 'integer',
            'active' => 'boolean',
        ]);

        $widget->update($validated);

        return $this->sendResponse([], 'Widget actualizado exitosamente');
    }
}
