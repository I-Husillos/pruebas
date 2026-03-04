<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Form\Application\Update\UpdateFormCommand;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar forms"
)]
final class FormPutController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Put(
        path: "/api/v1/forms/{id}",
        tags: ["Forms"],
        summary: "Actualizar Form",
        description: "Actualizar Form",
        operationId: "updateForm",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Form", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:forms,key,' . $id,
            'recipient_email' => 'nullable|email',
            'fields' => 'nullable|array',
            'active' => 'boolean',
        ]);

        $this->commandBus->dispatch(new UpdateFormCommand(
            $id,
            $request->name,
            $request->key,
            $request->recipient_email,
            $request->fields ?? [],
            $request->boolean('active')
        ));

        return $this->sendResponse([], 'Formulario actualizado exitosamente');
    }
}
