<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandBus;
use Termosalud\Web\Form\Application\Submit\SubmitFormCommand;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Forms",
    description: "Endpoints para gestionar formularios de contacto y captación"
)]
final class FormSubmitController extends ApiController
{
    public function __construct(private readonly CommandBus $commandBus) {}

    #[OA\Post(
        path: "/api/v1/forms/{key}/submit",
        tags: ["Forms"],
        summary: "Enviar respuestas de un formulario",
        operationId: "submitForm"
    )]
    #[OA\PathParameter(
        name: "key",
        description: "Slug/Key del formulario",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "data", description: "Campos del formulario", type: "object")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Formulario enviado con éxito"
    )]
    #[OA\Response(
        response: 404,
        description: "Formulario no encontrado"
    )]
    public function __invoke(Request $request, string $key): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new SubmitFormCommand(
                $key,
                $request->input('data', []),
                $request->ip(),
                $request->userAgent()
            ));

            return $this->sendResponse([], 'Form submitted successfully');
        } catch (\InvalidArgumentException $e) {
            return $this->sendError($e->getMessage(), [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error submitting form', ['error' => $e->getMessage()], 500);
        }
    }
}
