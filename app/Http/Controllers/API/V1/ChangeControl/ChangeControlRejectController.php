<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ChangeControl;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ChangeControl;
use App\Services\ChangeControlService;

#[OA\Tag(
    name: "Change Controls",
    description: "Endpoints para gestionar change controls"
)]
final class ChangeControlRejectController extends ApiController
{
    public function __construct(private readonly ChangeControlService $changeControlService) {}
    #[OA\Post(
        path: "/api/v1/change-controls/{id}/reject",
        tags: ["Change Controls"],
        summary: "Rechazar ChangeControl",
        description: "Rechazar ChangeControl",
        operationId: "rejectChangeControl",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ChangeControl", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, string $id): JsonResponse
    {
        $changeControl = ChangeControl::findOrFail($id);
        $this->authorize('approve', $changeControl);

        $reason = $request->input('reason');
        $this->changeControlService->reject($changeControl, $reason);

        return $this->sendResponse([], 'Cambio rechazado.');
    }
}
