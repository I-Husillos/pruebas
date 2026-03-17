<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\Page\StorePageRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\Page\Application\Create\CreatePageCommand;

#[OA\Tag(name: "Pages", description: "Endpoints para gestionar páginas")]
final class PagePostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Post(
        path: "/api/v1/pages",
        tags: ["Pages"],
        summary: "Crear página",
        operationId: "createPage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 201, description: "Página creada")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StorePageRequest $request): JsonResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new CreatePageCommand(
            (string) $v['status'],
            (array)  $v['localizations'],
        ));

        return $this->sendResponse([], 'Página creada exitosamente', 201);
    }
}
