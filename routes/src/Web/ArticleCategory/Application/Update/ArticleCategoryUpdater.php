<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Update;

use Termosalud\Web\ArticleCategory\Domain\ArticleCategory;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;

final class ArticleCategoryUpdater
{
    public function __construct(private readonly ArticleCategoryRepository $repository) {}

    public function __invoke(
        int $id,
        string $status,
        int $order,
        array $translations
    ): void {
        $category = new ArticleCategory($id, $status, $order, $translations);

        $this->repository->save($category);
    }
}
