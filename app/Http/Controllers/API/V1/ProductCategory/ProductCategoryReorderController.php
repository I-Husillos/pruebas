<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar product categories"
)]
final class ProductCategoryReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/product-categories/reorder",
        tags: ["Product Categories"],
        summary: "Reordenar Categorías de Productos",
        description: "Reordenar Categorías de Productos mediante drag & drop",
        operationId: "reorderProductCategories",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:product_categories,id',
            'items.*.order'  => 'required|integer|min:0',
        ])['items'];

        foreach ($items as $item) {
            ProductCategory::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
