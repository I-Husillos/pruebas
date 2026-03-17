<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\Page\UpdatePageRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\Page\Application\Update\UpdatePageCommand;

#[OA\Tag(name: "Pages", description: "Endpoints para gestionar páginas")]
final class PagePutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/pages/{id}",
        tags: ["Pages"],
        summary: "Actualizar página",
        operationId: "updatePage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de la página", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdatePageRequest $request, int $id): JsonResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new UpdatePageCommand(
            $id,
            (string) $v['status'],
            (array)  $v['localizations'],
        ));

        return $this->sendResponse([], 'Página actualizada exitosamente');
    }
}
