<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Article;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Article\StoreArticleRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Article\Application\Create\CreateArticleContentCommand;

#[OA\Tag(
    name: "Articles",
    description: "Endpoints para gestionar articles"
)]
final class ArticlePostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/articles",
        tags: ["Articles"],
        summary: "Crear Article",
        operationId: "createArticle",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new CreateArticleContentCommand(
            isset($validated['article_category_id']) ? (int) $validated['article_category_id'] : null,
            (string) ($validated['status'] ?? 'draft'),
            (array) ($validated['images'] ?? []),
            (array) $validated['localizations']
        ));

        return $this->sendResponse([], 'Artículo creado exitosamente', 201);
    }
}
