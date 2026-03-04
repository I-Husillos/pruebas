<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\User\Application\Delete\RemoveUserCommand;

#[OA\Tag(
    name: "Users",
    description: "Endpoints para gestionar users"
)]
final class UserDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Delete(
        path: "/api/v1/users/{id}",
        tags: ["Users"],
        summary: "Eliminar User",
        description: "Eliminar User",
        operationId: "deleteUser",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de User", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        if ($id === (int) \Illuminate\Support\Facades\Auth::id()) {
            return $this->sendError('Forbidden', ['error' => 'No puedes eliminar tu propio usuario.'], 403);
        }

        $this->commandBus->dispatch(new RemoveUserCommand($id));

        return $this->sendResponse([], 'Usuario eliminado exitosamente');
    }
}
