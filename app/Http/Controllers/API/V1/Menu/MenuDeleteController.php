<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Menu;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Menu;

#[OA\Tag(
    name: "Menus",
    description: "Endpoints para gestionar menus"
)]
final class MenuDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/menus/{id}",
        tags: ["Menus"],
        summary: "Eliminar Menu",
        description: "Eliminar Menu",
        operationId: "deleteMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Menu", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return $this->sendResponse([], 'Menú eliminado exitosamente');
    }
}
