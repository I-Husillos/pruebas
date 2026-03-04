<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Form\Application\Find\FindFormByIdQuery;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar forms"
)]
final class FormGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}
    #[OA\Get(
        path: "/api/v1/forms/{id}",
        tags: ["Forms"],
        summary: "Obtener Form",
        description: "Obtener Form",
        operationId: "getForm",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Form", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        /** @var \Termosalud\Forms\Application\FormResponse|null $response */
        $response = $this->queryBus->ask(new FindFormByIdQuery($id));

        if (! $response) {
            return $this->sendError('Formulario no encontrado', [], 404);
        }

        return $this->sendResponse($response->toArray(), 'Formulario recuperado exitosamente');
    }
}
