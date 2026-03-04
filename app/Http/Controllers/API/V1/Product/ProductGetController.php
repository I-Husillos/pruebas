<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Product\Application\Find\FindProductQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar productos de equipamiento médico estético"
)]
final class ProductGetController extends ApiController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {}

    #[OA\Get(
        path: "/api/v1/products/{id}",
        tags: ["Products"],
        summary: "Obtener producto por ID",
        description: "Retorna los detalles completos de un producto específico",
        operationId: "getProductById",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID único del producto",
        required: true,
        schema: new OA\Schema(type: "string", example: "01jf2x3y4z5a6b7c8d9e0f1g")
    )]
    #[OA\Response(
        response: 200,
        description: "Producto encontrado",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: true),
                new OA\Property(property: "message", type: "string", example: "Product retrieved successfully"),
                new OA\Property(property: "data", type: "object")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Producto no encontrado"
    )]
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var \Termosalud\Catalog\Application\ProductResponse|null $product */
            $product = $this->queryBus->ask(new FindProductQuery((int) $id));

            if ($product === null) {
                return $this->sendError('Product not found', [], 404);
            }

            return $this->sendResponse($product->toArray(), 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product', ['error' => $e->getMessage()], 500);
        }
    }
}
