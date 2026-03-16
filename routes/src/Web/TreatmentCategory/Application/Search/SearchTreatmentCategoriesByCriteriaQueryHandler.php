<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\TreatmentCategory\Application\TreatmentCategoriesResponse;
use Termosalud\Web\TreatmentCategory\Application\TreatmentCategoryResponse;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class SearchTreatmentCategoriesByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(SearchTreatmentCategoriesByCriteriaQuery $query): TreatmentCategoriesResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order = $query->orderBy()
            ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc'))
            : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $categories = $this->repository->searchByCriteria($criteria);
        $total = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($category) => TreatmentCategoryResponse::fromCategory($category), $categories);

        return new TreatmentCategoriesResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
