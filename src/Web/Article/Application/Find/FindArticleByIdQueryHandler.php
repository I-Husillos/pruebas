<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Article\Application\ArticleResponse;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class FindArticleByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(FindArticleByIdQuery $query): ?ArticleResponse
    {
        $article = $this->repository->findById((int) $query->id());

        return $article ? ArticleResponse::fromArticle($article) : null;
    }
}
