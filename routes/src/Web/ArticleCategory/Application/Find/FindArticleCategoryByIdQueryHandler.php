<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\ArticleCategory\Application\ArticleCategoryResponse;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;

final class FindArticleCategoryByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly ArticleCategoryRepository $repository) {}

    public function __invoke(FindArticleCategoryByIdQuery $query): ?ArticleCategoryResponse
    {
        $articleCategory = $this->repository->search($query->id());

        return $articleCategory ? ArticleCategoryResponse::fromCategory($articleCategory) : null;
    }
}
