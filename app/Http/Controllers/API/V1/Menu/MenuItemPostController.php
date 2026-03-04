<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Menu;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\MenuItem;

#[OA\Tag(
    name: "Menus",
    description: "Endpoints para gestionar menus"
)]
final class MenuItemPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/menus/{menu}/items",
        tags: ["Menus"],
        summary: "Crear Menu",
        description: "Crear Menu",
        operationId: "createItemMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "menu", description: "ID del Menú", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $menuId): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'required|array',
            'label.es' => 'required|string|max:255',
            'label.en' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'route_params' => 'nullable|array',
            'parent_id' => 'nullable|exists:menu_items,id',
            'icon' => 'nullable|string|max:100',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'integer',
            'active' => 'boolean',
        ]);

        $validated['menu_id'] = $menuId;

        MenuItem::create($validated);

        return $this->sendResponse([], 'Item de menú creado exitosamente', 201);
    }
}
