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
final class MenuPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/menus",
        tags: ["Menus"],
        summary: "Crear Menu",
        description: "Crear Menu",
        operationId: "createMenu",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'key' => 'required|string|max:255|unique:menus,key',
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
        ]);

        Menu::create($validated);

        return $this->sendResponse([], 'Menú creado exitosamente', 201);
    }
}
