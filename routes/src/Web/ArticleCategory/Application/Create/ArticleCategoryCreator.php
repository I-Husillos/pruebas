<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Create;

use Termosalud\Web\ArticleCategory\Domain\ArticleCategory;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;

final class ArticleCategoryCreator
{
    public function __construct(private readonly ArticleCategoryRepository $repository) {}

    public function __invoke(string $status, int $order, array $translations): void
    {
        $category = new ArticleCategory(0, $status, $order, $translations);

        $this->repository->save($category);
    }
}