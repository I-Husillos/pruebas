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
final class MarketPutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/markets/{id}",
        tags: ["Markets"],
        summary: "Actualizar Market",
        description: "Actualizar Market",
        operationId: "updateMarket",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Market", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $market = Market::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:markets,code,' . $id,
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'regulation_type' => 'required|string|max:100',
            'default_language' => 'required|string|max:10',
            'enabled_languages' => 'required|array',
            'currency' => 'required|string|max:10',
            'active' => 'boolean',
        ]);

        $market->update($validated);

        return $this->sendResponse([], 'Mercado actualizado exitosamente');
    }
}
