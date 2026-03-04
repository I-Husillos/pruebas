<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Form\Application\FormResponse;
use Termosalud\Web\Form\Application\FormsResponse;
use Termosalud\Web\Form\Domain\FormRepository;

final class SearchFormsByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(SearchFormsByCriteriaQuery $query): FormsResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $forms = $this->repository->searchByCriteria($criteria);
        $total = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($form) => FormResponse::fromForm($form), $forms);

        return new FormsResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
