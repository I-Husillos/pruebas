<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ProductCategory\Application\Delete\DeleteProductCategoryCommand;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar product categories"
)]
final class ProductCategoryDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Delete(
        path: "/api/v1/product-categories/{id}",
        tags: ["Product Categories"],
        summary: "Eliminar ProductCategory",
        description: "Eliminar ProductCategory",
        operationId: "deleteProductCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ProductCategory", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteProductCategoryCommand($id));

        return $this->sendResponse([], 'Categoría de producto eliminada exitosamente');
    }
}
