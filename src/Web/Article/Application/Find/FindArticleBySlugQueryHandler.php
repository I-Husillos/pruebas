<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Article\Application\ArticleResponse;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class FindArticleBySlugQueryHandler implements QueryHandler
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(FindArticleBySlugQuery $query): ?ArticleResponse
    {
        $article = $this->repository->findBySlug(
            $query->slug(),
            $query->languageId(),
            $query->marketId(),
        );

        return $article ? ArticleResponse::fromArticle($article) : null;
    }
}