<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\User\Application\Update\UpdateUserCommand;

#[OA\Tag(
    name: "Users",
    description: "Endpoints para gestionar users"
)]
final class UserPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Put(
        path: "/api/v1/users/{id}",
        tags: ["Users"],
        summary: "Actualizar User",
        description: "Actualizar User",
        operationId: "updateUser",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de User", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, string $id): JsonResponse
    {
        \Illuminate\Support\Facades\Log::info('PUT request', [
            'route_id' => $id,
            'request_all' => $request->all(),
            'url' => $request->fullUrl(),
        ]);

        if (!ctype_digit($id)) {
            return $this->sendError('ID inválido', [], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'array',
        ]);

        $this->commandBus->dispatch(new UpdateUserCommand(
            (int)$id,
            $request->name,
            $request->email,
            $request->password,
            $request->roles ?? []
        ));

        return $this->sendResponse([], 'Usuario actualizado exitosamente');
    }
}
