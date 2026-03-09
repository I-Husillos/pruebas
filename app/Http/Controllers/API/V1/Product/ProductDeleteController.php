<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Product;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Product\Application\Delete\DeleteProductCommand;

#[OA\Tag(
    name: "Products",
    description: "Endpoints para gestionar products"
)]
final class ProductDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Delete(
        path: "/api/v1/products/{id}",
        tags: ["Products"],
        summary: "Eliminar Product",
        description: "Eliminar Product",
        operationId: "deleteProduct",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Product", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteProductCommand($id));

        return $this->sendResponse([], 'Producto eliminado exitosamente');
    }
}
