<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\ArticleCategory\UpdateArticleCategoryRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ArticleCategory\Application\Update\UpdateArticleCategoryCommand;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/article-categories/{id}",
        tags: ["Article Categories"],
        summary: "Actualizar ArticleCategory",
        description: "Actualizar ArticleCategory",
        operationId: "updateArticleCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ArticleCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdateArticleCategoryRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateArticleCategoryCommand(
            $id,
            $validated['name'],
            $validated['slug'],
            $validated['description'] ?? null,
            (bool) ($validated['active'] ?? false),
            (int) ($validated['sort_order'] ?? 0),
        ));

        return $this->sendResponse([], 'Categoría de artículo actualizada exitosamente');
    }
}
