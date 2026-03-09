<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface ArticleCategoryRepository
{
    public function save(ArticleCategory $category): void;

    /** @return ArticleCategory[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;

    public function remove(int $id): void;

    public function search(int $id): ?ArticleCategory;

    public function findBySlug(string $slug, string $locale): ?ArticleCategory;

}
