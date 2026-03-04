<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ChangeControl;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ChangeControl;
use Illuminate\Support\Facades\Auth;

#[OA\Tag(
    name: "Change Controls",
    description: "Endpoints para gestionar change controls"
)]
final class ChangeControlPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/change-controls",
        tags: ["Change Controls"],
        summary: "Crear ChangeControl",
        description: "Crear ChangeControl",
        operationId: "createChangeControl",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('create', ChangeControl::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:draft,pending,approved,implemented',
            'reason' => 'nullable|string',
            'impact' => 'nullable|string',
        ]);

        $validated['requester_id'] = Auth::id();

        ChangeControl::create($validated);

        return $this->sendResponse([], 'Control de cambios creado exitosamente', 201);
    }
}
