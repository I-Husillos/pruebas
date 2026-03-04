<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Page\Application\ByIdQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Content",
    description: "Endpoints para gestionar contenido: artículos y páginas"
)]
final class PageGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/pages/{id}",
        tags: ["Content"],
        summary: "Ver página por ID",
        operationId: "getPageById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID de la página",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Página encontrada"
    )]
    #[OA\Response(
        response: 404,
        description: "Página no encontrada"
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Content\Application\PageResponse|null $page */
            $page = $this->queryBus->ask(new FindPageByIdQuery((int)$id));

            if (!$page) {
                return $this->sendError('Page not found', [], 404);
            }

            return $this->sendResponse($page->toArray(), 'Page retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving page', ['error' => $e->getMessage()], 500);
        }
    }
}
