<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Language;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Language;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Language\Application\Find\FindLanguageByCodeQuery;

#[OA\Tag(
    name: "Languages",
    description: "Endpoints para gestionar languages"
)]
final class LanguageGetController extends ApiController
{
        public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/languages/{id}",
        tags: ["Languages"],
        summary: "Obtener Language",
        description: "Obtener Language",
        operationId: "getLanguage",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(name: "id", description: "ID de Language", required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(int $id): JsonResponse
    {
        try {
            $language = $this->queryBus->ask(new FindLanguageByCodeQuery((string) $id));

            if (!$language) {
                return $this->sendError('Language not found', [], 404);
            }

            return $this->sendResponse($language->toArray(), 'Language retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving language', ['error' => $e->getMessage()], 500);
        }
    }
}
