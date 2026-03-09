<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Page\StorePageRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Page\Application\Create\CreatePageCommand;

#[OA\Tag(
    name: "Pages",
    description: "Endpoints para gestionar pages"
)]
final class PagePostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/pages",
        tags: ["Pages"],
        summary: "Crear Page",
        description: "Crear Page",
        operationId: "createPage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StorePageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $blocksJson = $validated['blocks_json'] ?? [];

        $this->commandBus->dispatch(new CreatePageCommand(
            $validated['market_code'],
            $validated['language_code'],
            $validated['slug'],
            (bool) ($validated['is_active'] ?? false),
            $validated['seo_title'] ?? null,
            $validated['seo_description'] ?? null,
            $blocksJson
        ));

        return $this->sendResponse([], 'Página creada exitosamente', 201);
    }
}
