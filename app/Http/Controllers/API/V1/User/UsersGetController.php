<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\User\Application\Search\SearchUsersByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Users",
    description: "Endpoints para gestionar usuarios administrativos (Protegido)"
)]
final class UsersGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/users",
        tags: ["Users"],
        summary: "Listar usuarios administrativos",
        operationId: "getUsers",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "search",
        description: "Búsqueda por nombre",
        required: false,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\QueryParameter(
        name: "order_by",
        description: "Campo por el que ordenar",
        required: false,
        schema: new OA\Schema(type: "string", default: "id")
    )]
    #[OA\QueryParameter(
        name: "order",
        description: "Dirección del orden (asc, desc)",
        required: false,
        schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "desc")
    )]
    #[OA\QueryParameter(
        name: "limit",
        description: "Límite de resultados",
        required: false,
        schema: new OA\Schema(type: "integer", default: 15)
    )]
    #[OA\QueryParameter(
        name: "offset",
        description: "Desplazamiento para paginación",
        required: false,
        schema: new OA\Schema(type: "integer", default: 0)
    )]
    #[OA\Response(
        response: 200,
        description: "Lista de usuarios administrativos"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\User\Application\UsersResponse $response */
            $response = $this->queryBus->ask(new SearchUsersByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving users', ['error' => $e->getMessage()], 500);
        }
    }
}
