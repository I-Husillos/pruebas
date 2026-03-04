<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Form\Application\Create\CreateFormCommand;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar forms"
)]
final class FormPostController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}
    #[OA\Post(
        path: "/api/v1/forms",
        tags: ["Forms"],
        summary: "Crear Form",
        description: "Crear Form",
        operationId: "createForm",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:forms',
            'recipient_email' => 'nullable|email',
            'fields' => 'nullable|array',
            'active' => 'boolean',
        ]);

        $this->commandBus->dispatch(new CreateFormCommand(
            $request->name,
            $request->key,
            $request->recipient_email,
            $request->fields ?? [],
            $request->boolean('active', true)
        ));

        return $this->sendResponse([], 'Formulario creado exitosamente', 201);
    }
}
