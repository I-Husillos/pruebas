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

        // El controlador ya está bien estructurado.
        // Solo asegurarse de que StoreArticleRequest esté corregido.
        $this->commandBus->dispatch(new CreateArticleContentCommand(
            (int) ($validated['article_category_id'] ?? 0),
            (string) $validated['status'],
            (array) ($validated['images'] ?? []),
            (array) $validated['localizations']  // ← Este array llegará limpio del Request
        ));

        return $this->sendResponse([], 'Artículo creado exitosamente', 201);
    }
}
