<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Menu;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Menu;

#[OA\Tag(
    name: "Menus",
    description: "Endpoints para gestionar menus"
)]
final class MenuPutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/menus/{id}",
        tags: ["Menus"],
        summary: "Actualizar Menu",
        description: "Actualizar Menu",
        operationId: "updateMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Menu", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'key' => 'required|string|max:255|unique:menus,key,' . $id,
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $menu->update($validated);

        return $this->sendResponse([], 'Menú actualizado exitosamente');
    }
}
