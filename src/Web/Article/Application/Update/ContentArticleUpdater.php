<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Update;

use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class ContentArticleUpdater
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(
        int $id,
        ?int $articleCategoryId,
        string $status,
        array $images,
        array $localizations,
    ): void
    {
        $contentArticle = new ContentArticle(
            $id,
            $articleCategoryId,
            $status,
            $images,
            $localizations,
            null,
            null,
            null
        );
        $this->repository->save($contentArticle);
    }
}
