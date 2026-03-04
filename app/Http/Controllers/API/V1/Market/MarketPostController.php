<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Market;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Market;

#[OA\Tag(
    name: "Markets",
    description: "Endpoints para gestionar markets"
)]
final class MarketPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/markets",
        tags: ["Markets"],
        summary: "Crear Market",
        description: "Crear Market",
        operationId: "createMarket",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:markets,code',
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'regulation_type' => 'required|string|max:100',
            'default_language' => 'required|string|max:10',
            'enabled_languages' => 'required|array',
            'currency' => 'required|string|max:10',
            'active' => 'boolean',
        ]);

        Market::create($validated);

        return $this->sendResponse([], 'Mercado creado exitosamente', 201);
    }
}
