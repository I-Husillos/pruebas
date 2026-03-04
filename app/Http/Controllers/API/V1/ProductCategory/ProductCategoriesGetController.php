<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\ProductCategory\Application\Search\SearchProductCategoriesByCriteriaQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Catalog",
    description: "Endpoints para gestionar el catálogo de productos y categorías"
)]
final class ProductCategoriesGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/product-categories",
        tags: ["Catalog"],
        summary: "Listar categorías de productos",
        operationId: "getProductCategories",
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
        schema: new OA\Schema(type: "string", default: "sort_order")
    )]
    #[OA\QueryParameter(
        name: "order",
        description: "Dirección del orden (asc, desc)",
        required: false,
        schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "asc")
    )]
    #[OA\QueryParameter(
        name: "limit",
        description: "Límite de resultados",
        required: false,
        schema: new OA\Schema(type: "integer", default: 100)
    )]
    #[OA\QueryParameter(
        name: "offset",
        description: "Desplazamiento para paginación",
        required: false,
        schema: new OA\Schema(type: "integer", default: 0)
    )]
    #[OA\Response(
        response: 200,
        description: "Lista de categorías de productos"
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var \Termosalud\Web\ProductCategory\Application\ProductCategoriesResponse $response */
            $response = $this->queryBus->ask(new SearchProductCategoriesByCriteriaQuery(
                $request->input('filters', []),
                $request->query('order_by', 'sort_order'),
                $request->query('order', 'asc'),
                (int) $request->query('limit', 100),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Product categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving product categories', ['error' => $e->getMessage()], 500);
        }
    }
}
