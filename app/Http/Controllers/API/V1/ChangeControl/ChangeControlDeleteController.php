<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ChangeControl;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\ChangeControl;

#[OA\Tag(
    name: "Change Controls",
    description: "Endpoints para gestionar change controls"
)]
final class ChangeControlDeleteController extends ApiController
{
    #[OA\Delete(
        path: "/api/v1/change-controls/{id}",
        tags: ["Change Controls"],
        summary: "Eliminar ChangeControl",
        description: "Eliminar ChangeControl",
        operationId: "deleteChangeControl",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ChangeControl", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(string $id): JsonResponse
    {
        $changeControl = ChangeControl::findOrFail($id);
        $this->authorize('delete', $changeControl);

        $changeControl->delete();

        return $this->sendResponse([], 'Control de cambios eliminado exitosamente');
    }
}
