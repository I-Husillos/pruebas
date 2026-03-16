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
                isset($validated['product_category_id']) ? (int) $validated['product_category_id'] : null,
                (string) $validated['code'],
                (string) ($validated['status'] ?? 'draft'),
                (array) ($validated['images'] ?? []),
                (array) $validated['localizations'],
                $validated['related_treatments'] ?? null,
                (int) ($validated['order'] ?? 0),
        ));

        return $this->sendResponse([], 'Producto creado exitosamente', 201);
    }
}
