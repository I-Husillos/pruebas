<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Widget;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Widget;

#[OA\Tag(
    name: "Widgets",
    description: "Endpoints para gestionar widgets"
)]
final class WidgetDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/widgets/{id}",
        tags: ["Widgets"],
        summary: "Eliminar Widget",
        description: "Eliminar Widget",
        operationId: "deleteWidget",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Widget", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $widget = Widget::findOrFail($id);
        $widget->delete();

        return $this->sendResponse([], 'Widget eliminado exitosamente');
    }
}
