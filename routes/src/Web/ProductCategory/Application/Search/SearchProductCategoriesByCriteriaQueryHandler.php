<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\ProductCategory\Application\ProductCategoryResponse;
use Termosalud\Web\ProductCategory\Application\ProductCategoriesResponse;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;

final class SearchProductCategoriesByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(SearchProductCategoriesByCriteriaQuery $query): ProductCategoriesResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $categories = $this->repository->searchByCriteria($criteria);
        $total      = $this->repository->countByCriteria($criteria);

        $responses = array_map(
            fn($category) => ProductCategoryResponse::fromCategory($category),
            $categories
        );

        return new ProductCategoriesResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
