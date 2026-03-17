<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Page\Application\Delete\RemovePageCommand;

#[OA\Tag(
    name: "Pages",
    description: "Endpoints para gestionar pages"
)]
final class PageDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Delete(
        path: "/api/v1/pages/{id}",
        tags: ["Pages"],
        summary: "Eliminar Page",
        description: "Eliminar Page",
        operationId: "deletePage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Page", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new RemovePageCommand($id));

        return $this->sendResponse([], 'Página eliminada exitosamente');
    }
}
