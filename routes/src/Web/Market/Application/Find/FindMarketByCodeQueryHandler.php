<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Market\Application\MarketResponse;
use Termosalud\Web\Market\Domain\MarketRepository;

final class FindMarketByCodeQueryHandler implements QueryHandler
{
    public function __construct(private readonly MarketRepository $repository) {}

    public function __invoke(FindMarketByCodeQuery $query): ?MarketResponse
    {
        $market = $this->repository->findByCode($query->code());

        return $market ? MarketResponse::fromMarket($market) : null;
    }
}
