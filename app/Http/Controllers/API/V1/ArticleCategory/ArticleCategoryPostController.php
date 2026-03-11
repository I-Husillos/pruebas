<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\ArticleCategory\StoreArticleCategoryRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\ArticleCategory;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\ArticleCategory\Application\Create\CreateArticleCategoryCommand;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/article-categories",
        tags: ["Article Categories"],
        summary: "Crear ArticleCategory",
        description: "Crear ArticleCategory",
        operationId: "createArticleCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreArticleCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $nextOrder = (ArticleCategory::max('order') ?? -1) + 1;

        $this->commandBus->dispatch(new CreateArticleCategoryCommand(
            $validated['status'],
            $nextOrder,
            $validated['translations'],
        ));

        return $this->sendResponse([], 'Categoría de artículo creada exitosamente', 201);
    }
}
