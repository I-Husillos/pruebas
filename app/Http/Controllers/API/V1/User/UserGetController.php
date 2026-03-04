<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\User\Application\Find\FindUserByIdQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Users",
    description: "Endpoints para gestionar usuarios administrativos (Protegido)"
)]
final class UserGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/users/{id}",
        tags: ["Users"],
        summary: "Ver usuario por ID",
        operationId: "getUserById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID del usuario",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Usuario encontrado"
    )]
    #[OA\Response(
        response: 404,
        description: "Usuario no encontrado"
    )]
    public function __invoke(int $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Users\Application\UserResponse|null $user */
            $user = $this->queryBus->ask(new FindUserByIdQuery($id));

            if (!$user) {
                return $this->sendError('User not found', [], 404);
            }

            return $this->sendResponse($user->toArray(), 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving user', ['error' => $e->getMessage()], 500);
        }
    }
}
