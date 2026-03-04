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
final class MenuItemPutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/menus/items/{id}",
        tags: ["Menus"],
        summary: "Actualizar Menu",
        description: "Actualizar Menu",
        operationId: "updateItemMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Menu", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $item = MenuItem::findOrFail($id);

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

        $item->update($validated);

        return $this->sendResponse([], 'Item de menú actualizado exitosamente');
    }
}
