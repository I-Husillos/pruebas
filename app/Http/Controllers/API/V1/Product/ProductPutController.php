<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Product\Application\Update\UpdateProductCommand;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar products"
)]
final class ProductPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Put(
        path: "/api/v1/products/{id}",
        tags: ["Products"],
        summary: "Actualizar Product",
        description: "Actualizar Product",
        operationId: "updateProduct",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Product", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(UpdateProductRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $this->commandBus->dispatch(new UpdateProductCommand(
            $id,
                isset($validated['product_category_id']) ? (int) $validated['product_category_id'] : null,
                (string) $validated['code'],
                (string) $validated['status'],
                (array) ($validated['images'] ?? []),
                (array) $validated['localizations'],
                $validated['related_treatments'] ?? null,
                (int) ($validated['order'] ?? 0),
        ));

        return $this->sendResponse([], 'Producto actualizado exitosamente');
    }
}
