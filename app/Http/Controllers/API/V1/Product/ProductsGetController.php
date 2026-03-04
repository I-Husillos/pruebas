<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Product\Application\Search\SearchProductsByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar productos de equipamiento médico estético"
)]
final class ProductsGetController extends ApiController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {}

    #[OA\Get(
        path: "/api/v1/products",
        tags: ["Products"],
        summary: "Listar productos",
        description: "Obtiene la lista de todos los productos disponibles",
        operationId: "getProducts",
        security: [["bearerAuth" => []]]
    )]
    #[OA\QueryParameter(
        name: "search",
        description: "Búsqueda por nombre",
        required: false,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\QueryParameter(
        name: "order_by",
        description: "Campo por el que ordenar",
        required: false,
        schema: new OA\Schema(type: "string", default: "id")
    )]
    #[OA\QueryParameter(
        name: "order",
        description: "Dirección del orden (asc, desc)",
        required: false,
        schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "desc")
    )]
    #[OA\QueryParameter(
        name: "limit",
        description: "Límite de resultados",
        required: false,
        schema: new OA\Schema(type: "integer", default: 15)
    )]
    #[OA\QueryParameter(
        name: "offset",
        description: "Desplazamiento para paginación",
        required: false,
        schema: new OA\Schema(type: "integer", default: 0)
    )]
    #[OA\Response(
        response: 200,
        description: "Lista de productos obtenida exitosamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: true),
                new OA\Property(property: "message", type: "string", example: "Data retrieved successfully"),
                new OA\Property(
                    property: "data",
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "string", example: "01jf2x3y4z5a6b7c8d9e0f1g"),
                            new OA\Property(
                                property: "name",
                                properties: [
                                    new OA\Property(property: "es", type: "string", example: "Zionic Pro Max"),
                                    new OA\Property(property: "en", type: "string", example: "Zionic Pro Max")
                                ],
                                type: "object"
                            ),
                            new OA\Property(property: "sku", type: "string", example: "ZIONIC-PRO-MAX"),
                            new OA\Property(property: "is_featured", type: "boolean", example: true),
                            new OA\Property(property: "status", type: "boolean", example: true)
                        ],
                        type: "object"
                    )
                )
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 401,
        description: "No autenticado",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "success", type: "boolean", example: false),
                new OA\Property(property: "message", type: "string", example: "Unauthenticated")
            ],
            type: "object"
        )
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\Product\Application\ProductsResponse $response */
            $response = $this->queryBus->ask(new SearchProductsByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving products', ['error' => $e->getMessage()], 500);
        }
    }
}
