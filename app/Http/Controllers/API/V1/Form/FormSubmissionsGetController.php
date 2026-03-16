<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Form;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Form\Application\Find\SearchFormSubmissionsByCriteriaQuery;
use OpenApi\Attributes as OA;


final class FormSubmissionsGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/forms/{id}/submissions",
        tags: ["Forms"],
        summary: "Listar envíos de un formulario",
        operationId: "getFormSubmissions",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "id",
        description: "ID del formulario",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\QueryParameter(name: "limit",  required: false, schema: new OA\Schema(type: "integer", default: 15))]
    #[OA\QueryParameter(name: "offset", required: false, schema: new OA\Schema(type: "integer", default: 0))]
    #[OA\Response(response: 200, description: "Envíos obtenidos exitosamente")]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $response = $this->queryBus->ask(new SearchFormSubmissionsByCriteriaQuery(
                // Filtramos por el form_id para traer solo los envíos de este formulario
                [['field' => 'form_id', 'operator' => '=', 'value' => $id]],
                $request->query('order_by', 'id'),
                $request->query('order', 'desc'),
                (int) $request->query('limit', 15),
                (int) $request->query('offset', 0)
            ));

            return $this->sendResponse($response->toArray(), 'Submissions retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving submissions', ['error' => $e->getMessage()], 500);
        }
    }
}