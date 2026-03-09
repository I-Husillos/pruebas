<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ArticleCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ArticleCategory\Application\Delete\DeleteArticleCategoryCommand;

#[OA\Tag(
    name: "Article Categories",
    description: "Endpoints para gestionar article categories"
)]
final class ArticleCategoryDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Delete(
        path: "/api/v1/article-categories/{id}",
        tags: ["Article Categories"],
        summary: "Eliminar ArticleCategory",
        description: "Eliminar ArticleCategory",
        operationId: "deleteArticleCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ArticleCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteArticleCategoryCommand($id));

        return $this->sendResponse([], 'Categoría de artículo eliminada exitosamente');
    }
}
