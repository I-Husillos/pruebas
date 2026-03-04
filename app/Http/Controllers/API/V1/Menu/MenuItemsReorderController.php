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
final class MenuItemsReorderController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/menus/items/items/reorder",
        tags: ["Menus"],
        summary: "Reordenar Menu",
        description: "Reordenar Menu",
        operationId: "reorderMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items' => 'required|array',
        ])['items'];

        $this->updateItemsOrder($items);

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }

    private function updateItemsOrder(array $items, ?int $parentId = null): void
    {
        foreach ($items as $index => $itemData) {
            $item = MenuItem::find($itemData['id']);
            if ($item) {
                $item->update([
                    'sort_order' => $index,
                    'parent_id' => $parentId,
                ]);

                if (isset($itemData['children']) && is_array($itemData['children'])) {
                    $this->updateItemsOrder($itemData['children'], $item->id);
                }
            }
        }
    }
}
