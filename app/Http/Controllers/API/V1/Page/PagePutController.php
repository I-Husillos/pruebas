<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Page\UpdatePageRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Page\Application\Update\UpdatePageCommand;

#[OA\Tag(
    name: "Pages",
    description: "Endpoints para gestionar pages"
)]
final class PagePutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Put(
        path: "/api/v1/pages/{id}",
        tags: ["Pages"],
        summary: "Actualizar Page",
        description: "Actualizar Page",
        operationId: "updatePage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Page", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdatePageRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $blocksJson = $validated['blocks_json'] ?? [];

        $this->commandBus->dispatch(new UpdatePageCommand(
            $id,
            $validated['market_code'],
            $validated['language_code'],
            $validated['slug'],
            (bool) ($validated['is_active'] ?? false),
            $validated['seo_title'] ?? null,
            $validated['seo_description'] ?? null,
            $blocksJson
        ));

        return $this->sendResponse([], 'Página actualizada exitosamente');
    }
}
