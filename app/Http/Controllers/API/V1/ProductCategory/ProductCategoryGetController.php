<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\ProductCategory\Application\Find\FindProductCategoryQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar categorías de productos"
)]
final class ProductCategoryGetController extends ApiController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {}

    #[OA\Get(
        path: "/api/v1/product-categories/{id}",
        tags: ["Product Categories"],
        summary: "Obtener categoría por ID",
        description: "Retorna los detalles de una categoría de producto específica",
        operationId: "getProductCategoryById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID único de la categoría",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Categoría encontrada",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: true),
                new OA\Property(property: "message", type: "string", example: "Category retrieved successfully"),
                new OA\Property(property: "data", type: "object")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Categoría no encontrada"
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\ProductCategory\Application\ProductCategoryResponse|null $category */
            $category = $this->queryBus->ask(new FindProductCategoryQuery((int) $id));

            if ($category === null) {
                return $this->sendError('Category not found', [], 404);
            }

            return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving category', ['error' => $e->getMessage()], 500);
        }
    }
}
