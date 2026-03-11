<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\ProductCategory\StoreProductCategoryRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Termosalud\Web\ProductCategory\Application\Create\CreateProductCategoryCommand;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar categorías de productos"
)]
final class ProductCategoryPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Post(
        path: "/api/v1/product-categories",
        tags: ["Product Categories"],
        summary: "Crear ProductCategory",
        description: "Crear ProductCategory",
        operationId: "createProductCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 201, description: "Creado")]
    #[OA\Response(response: 401, description: "No autenticado")]
    #[OA\Response(response: 422, description: "Error de validación")]
    public function __invoke(StoreProductCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new CreateProductCategoryCommand(
            $validated['status'],
            (int) ($validated['order'] ?? 0),
            $validated['translations'],
        ));

        return $this->sendResponse([], 'Categoría de producto creada exitosamente', 201);
    }
}
