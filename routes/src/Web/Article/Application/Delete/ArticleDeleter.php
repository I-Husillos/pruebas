<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Delete;

use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class ArticleDeleter
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
