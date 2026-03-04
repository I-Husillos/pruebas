<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Market;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Market;

#[OA\Tag(
    name: "Markets",
    description: "Endpoints para gestionar markets"
)]
final class MarketDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/markets/{id}",
        tags: ["Markets"],
        summary: "Eliminar Market",
        description: "Eliminar Market",
        operationId: "deleteMarket",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Market", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $market = Market::findOrFail($id);
        $market->delete();

        return $this->sendResponse([], 'Mercado eliminado exitosamente');
    }
}
