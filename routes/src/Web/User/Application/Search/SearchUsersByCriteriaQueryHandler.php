<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\User\Application\UserResponse;
use Termosalud\Web\User\Application\UsersResponse;
use Termosalud\Web\User\Domain\UserRepository;

final class SearchUsersByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly UserRepository $repository) {}

    public function __invoke(SearchUsersByCriteriaQuery $query): UsersResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $users = $this->repository->searchByCriteria($criteria);
        $total = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($user) => UserResponse::fromUser($user), $users);

        return new UsersResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
