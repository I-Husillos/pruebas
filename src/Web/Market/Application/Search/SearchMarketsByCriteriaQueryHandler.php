<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Market\Application\MarketResponse;
use Termosalud\Web\Market\Application\MarketsResponse;
use Termosalud\Web\Market\Domain\MarketRepository;

final class SearchMarketsByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly MarketRepository $repository) {}

    public function __invoke(SearchMarketsByCriteriaQuery $query): MarketsResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $markets = $this->repository->searchByCriteria($criteria);
        $total   = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($market) => MarketResponse::fromMarket($market), $markets);

        return new MarketsResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
