<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Language\Application\LanguageResponse;
use Termosalud\Web\Language\Application\LanguagesResponse;
use Termosalud\Web\Language\Domain\LanguageRepository;

final class SearchLanguagesByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly LanguageRepository $repository) {}

    public function __invoke(SearchLanguagesByCriteriaQuery $query): LanguagesResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $languages = $this->repository->searchByCriteria($criteria);
        $total     = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($language) => LanguageResponse::fromLanguage($language), $languages);

        return new LanguagesResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
