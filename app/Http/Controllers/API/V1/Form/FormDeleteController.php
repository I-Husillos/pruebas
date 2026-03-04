<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Form\Application\Delete\RemoveFormCommand;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar forms"
)]
final class FormDeleteController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Delete(
        path: "/api/v1/forms/{id}",
        tags: ["Forms"],
        summary: "Eliminar Form",
        description: "Eliminar Form",
        operationId: "deleteForm",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Form", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new RemoveFormCommand($id));

        return $this->sendResponse([], 'Formulario eliminado exitosamente');
    }
}
