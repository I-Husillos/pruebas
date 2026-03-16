<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Treatment\Application\TreatmentResponse;
use Termosalud\Web\Treatment\Application\TreatmentsResponse;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class SearchTreatmentsByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(SearchTreatmentsByCriteriaQuery $query): TreatmentsResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $treatments = $this->repository->searchByCriteria($criteria);
        $total      = $this->repository->countByCriteria($criteria);

        $responses = array_map(
            fn($treatment) => TreatmentResponse::fromTreatment($treatment),
            $treatments
        );

        return new TreatmentsResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
