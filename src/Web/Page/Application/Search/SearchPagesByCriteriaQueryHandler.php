<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Page\Application\PageResponse;
use Termosalud\Web\Page\Application\PagesResponse;
use Termosalud\Web\Page\Domain\PageRepository;

final class SearchPagesByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(SearchPagesByCriteriaQuery $query): PagesResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $pages = $this->repository->searchByCriteria($criteria);
        $total = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($page) => PageResponse::fromPage($page), $pages);

        return new PagesResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
