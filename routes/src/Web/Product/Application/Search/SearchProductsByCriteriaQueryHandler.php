<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Product\Application\ProductResponse;
use Termosalud\Web\Product\Application\ProductsResponse;
use Termosalud\Web\Product\Domain\ProductRepository;

final class SearchProductsByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(SearchProductsByCriteriaQuery $query): ProductsResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $products = $this->repository->searchByCriteria($criteria);
        $total    = $this->repository->countByCriteria($criteria);

        $responses = array_map(
            fn($product) => ProductResponse::fromProduct($product),
            $products
        );

        return new ProductsResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
