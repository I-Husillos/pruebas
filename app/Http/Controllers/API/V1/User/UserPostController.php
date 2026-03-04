<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\User\Application\Create\CreateUserCommand;

#[OA\Tag(
    name: "Users",
    description: "Endpoints para gestionar users"
)]
final class UserPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/users",
        tags: ["Users"],
        summary: "Crear User",
        description: "Crear User",
        operationId: "createUser",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'array',
        ]);

        $this->commandBus->dispatch(new CreateUserCommand(
            $request->name,
            $request->email,
            $request->password,
            $request->roles ?? []
        ));

        return $this->sendResponse([], 'Usuario creado exitosamente', 201);
    }
}
