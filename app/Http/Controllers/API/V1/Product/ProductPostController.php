<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Product\Application\Create\CreateProductCommand;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar products"
)]
final class ProductPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/products",
        tags: ["Products"],
        summary: "Crear Product",
        description: "Crear Product",
        operationId: "createProduct",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new CreateProductCommand(
            0, // New products don't have an ID yet
            $validated['code'],
            $validated['name'],
            $validated['slug'],
            $validated['short_description'] ?? null,
            $validated['description'] ?? null,
            $validated['technical_specs'] ?? null,
            $validated['images'] ?? null,
            isset($validated['category_id']) ? (int) $validated['category_id'] : null,
            $validated['category_name'] ?? null,
            $validated['tags'] ?? null,
            (bool) ($validated['published'] ?? false),
            $validated['published_at'] ?? null,
            $validated['available_markets'] ?? null,
            $validated['meta_seo'] ?? null,
            $validated['sort_order'] ?? 0
        ));

        return $this->sendResponse([], 'Producto creado exitosamente', 201);
    }
}
