<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class ContentArticleCreator
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(
        int $articleCategoryId,
        string $status,
        array $images,
        array $localizations,
    ): void {
        $contentArticle = new ContentArticle(
            null,
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