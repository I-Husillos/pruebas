<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\ProductCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

#[OA\Tag(
    name: "Product Categories",
    description: "Endpoints para gestionar product categories"
)]
final class ProductCategoryPostController extends ApiController
{
    #[OA\Post(
        path: "/api/v1/product-categories",
        tags: ["Product Categories"],
        summary: "Crear ProductCategory",
        description: "Crear ProductCategory",
        operationId: "createProductCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'slug.en' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        ProductCategory::create($validated);

        return $this->sendResponse([], 'Categoría de producto creada exitosamente', 201);
    }
}
