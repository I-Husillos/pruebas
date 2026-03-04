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
final class WidgetPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/widgets",
        tags: ["Widgets"],
        summary: "Crear Widget",
        description: "Crear Widget",
        operationId: "createWidget",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
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

        Widget::create($validated);

        return $this->sendResponse([], 'Widget creado exitosamente', 201);
    }
}
