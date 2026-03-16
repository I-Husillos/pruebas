<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Delete;

use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;

final class ArticleCategoryDeleter
{
    public function __construct(private readonly ArticleCategoryRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
