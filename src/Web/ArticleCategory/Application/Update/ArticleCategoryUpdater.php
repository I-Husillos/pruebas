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
        array $name,
        array $slug,
        ?array $description,
        bool $active,
        int $sortOrder
    ): void {
        $category = new ArticleCategory($id, $name, $slug, $description, $active, $sortOrder);

        $this->repository->save($category);
    }
}
