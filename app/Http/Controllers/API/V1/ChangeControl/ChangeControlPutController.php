<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ChangeControl;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ChangeControl;

#[OA\Tag(
    name: "Change Controls",
    description: "Endpoints para gestionar change controls"
)]
final class ChangeControlPutController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/change-controls/{id}",
        tags: ["Change Controls"],
        summary: "Actualizar ChangeControl",
        description: "Actualizar ChangeControl",
        operationId: "updateChangeControl",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ChangeControl", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, string $id): JsonResponse
    {
        $changeControl = ChangeControl::findOrFail($id);
        $this->authorize('update', $changeControl);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:draft,pending,approved,implemented,rejected',
            'reason' => 'nullable|string',
            'impact' => 'nullable|string',
        ]);

        $changeControl->update($validated);

        return $this->sendResponse([], 'Control de cambios actualizado exitosamente');
    }
}
