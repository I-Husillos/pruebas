<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ChangeControl;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\ChangeControl;
use App\Services\ChangeControlService;

#[OA\Tag(
    name: "Change Controls",
    description: "Endpoints para gestionar change controls"
)]
final class ChangeControlApproveController extends ApiController
{
    public function __construct(private readonly ChangeControlService $changeControlService) {}
    #[OA\Post(
        path: "/api/v1/change-controls/{id}/approve",
        tags: ["Change Controls"],
        summary: "Aprobar ChangeControl",
        description: "Aprobar ChangeControl",
        operationId: "approveChangeControl",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ChangeControl", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(string $id): JsonResponse
    {
        $changeControl = ChangeControl::findOrFail($id);
        $this->authorize('approve', $changeControl);

        if ($changeControl->status !== ChangeControl::STATUS_PENDING) {
            return $this->sendError('Solo se pueden aprobar cambios pendientes.', [], 422);
        }

        $this->changeControlService->approve($changeControl);

        return $this->sendResponse([], 'Cambio aprobado y aplicado exitosamente.');
    }
}
