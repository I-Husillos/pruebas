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
        description: "Crear Article",
        operationId: "createArticle",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $publishedAt = isset($validated['published_at'])
            ? new \DateTimeImmutable((string) $validated['published_at'])
            : null;

        $this->commandBus->dispatch(new CreateArticleContentCommand(
            0, // New articles don't have an ID yet
            $validated['type'],
            $validated['title'],
            $validated['slug'],
            $validated['excerpt'] ?? null,
            $validated['content'] ?? null,
            $validated['author'] ?? 'admin',
            (bool) ($validated['published'] ?? false),
            isset($validated['category_id']) ? (int) $validated['category_id'] : null,
            $publishedAt
        ));

        return $this->sendResponse([], 'Artículo creado exitosamente', 201);
    }
}
