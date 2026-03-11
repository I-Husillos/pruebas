<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar products"
)]
final class ProductReorderController extends ApiController
{
    #[OA\Put(
        path: "/api/v1/products/reorder",
        tags: ["Products"],
        summary: "Reordenar Productos",
        description: "Reordenar Productos mediante drag & drop",
        operationId: "reorderProducts",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $items = $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.order'  => 'required|integer|min:0',
        ])['items'];

        foreach ($items as $item) {
            Product::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return $this->sendResponse([], 'Orden actualizado exitosamente');
    }
}
