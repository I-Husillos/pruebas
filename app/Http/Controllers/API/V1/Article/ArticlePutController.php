<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Article\UpdateArticleRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Article\Application\Update\UpdateContentArticleCommand;

#[OA\Tag(
    name: "Articles",
    description: "Endpoints para gestionar articles"
)]
final class ArticlePutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/articles/{id}",
        tags: ["Articles"],
        summary: "Actualizar Article",
        description: "Actualizar Article",
        operationId: "updateArticle",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Article", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateContentArticleCommand(
            $id,
            (int) ($validated['article_category_id'] ?? 0),
            (string) $validated['status'],
            (array) ($validated['images'] ?? []),
            (array) $validated['localizations']
        ));

        return $this->sendResponse([], 'Artículo actualizado exitosamente');
    }
}
