<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Market;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Market\Application\Find\FindMarketByCodeQuery;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Markets",
    description: "Endpoints para gestionar mercados geográficos"
)]
final class MarketGetController extends ApiController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    #[OA\Get(
        path: "/api/v1/markets/{code}",
        tags: ["Markets"],
        summary: "Ver mercado por código",
        operationId: "getMarketByCode",
        security: [["bearerAuth" => []]]
    )]
    #[OA\PathParameter(
        name: "code",
        description: "Código del mercado (es, mx, us, fr)",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Mercado encontrado"
    )]
    #[OA\Response(
        response: 404,
        description: "Mercado no encontrado"
    )]
    public function __invoke(string $code): JsonResponse
    {
        try {
            /** @var \Termosalud\GeoTargeting\Application\MarketResponse|null $market */
            $market = $this->queryBus->ask(new FindMarketByCodeQuery($code));

            if (!$market) {
                return $this->sendError('Market not found', [], 404);
            }

            return $this->sendResponse($market->toArray(), 'Market retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving market', ['error' => $e->getMessage()], 500);
        }
    }
}
