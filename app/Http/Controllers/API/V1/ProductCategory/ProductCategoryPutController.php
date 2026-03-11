<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\ProductCategory\UpdateProductCategoryRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ProductCategory\Application\Update\UpdateProductCategoryCommand;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar categorías de productos"
)]
final class ProductCategoryPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Put(
        path: "/api/v1/product-categories/{id}",
        tags: ["Product Categories"],
        summary: "Actualizar ProductCategory",
        description: "Actualizar ProductCategory",
        operationId: "updateProductCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de ProductCategory", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    #[OA\Response(response: 422, description: "Error de validación")]
    public function __invoke(UpdateProductCategoryRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateProductCategoryCommand(
            $id,
            $validated['status'],
            (int) ($validated['order'] ?? 0),
            $validated['translations'],
        ));

        return $this->sendResponse([], 'Categoría de producto actualizada exitosamente');
    }
}
