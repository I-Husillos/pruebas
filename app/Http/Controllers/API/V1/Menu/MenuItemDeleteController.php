<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Menu;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\MenuItem;

#[OA\Tag(
    name: "Menus",
    description: "Endpoints para gestionar menus"
)]
final class MenuItemDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/menus/items/{id}",
        tags: ["Menus"],
        summary: "Eliminar Menu",
        description: "Eliminar Menu",
        operationId: "deleteItemMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Menu", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();

        return $this->sendResponse([], 'Item de menú eliminado exitosamente');
    }
}
