<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Domain\Criteria\Filters;
use Dba\DddSkeleton\Shared\Domain\Criteria\Order;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderBy;
use Dba\DddSkeleton\Shared\Domain\Criteria\OrderType;
use Termosalud\Web\Article\Application\ArticleResponse;
use Termosalud\Web\Article\Application\ArticlesResponse;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class SearchArticlesByCriteriaQueryHandler implements QueryHandler
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(SearchArticlesByCriteriaQuery $query): ArticlesResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order   = $query->orderBy() ? new Order(new OrderBy($query->orderBy()), new OrderType($query->orderType() ?? 'asc')) : Order::none();

        $criteria = new Criteria($filters, $order, $query->offset(), $query->limit());

        $articles = $this->repository->searchByCriteria($criteria);
        $total    = $this->repository->countByCriteria($criteria);

        $responses = array_map(fn($article) => ArticleResponse::fromArticle($article), $articles);

        return new ArticlesResponse($responses, $total, $query->limit() ?? 15, $query->offset() ?? 0);
    }
}
