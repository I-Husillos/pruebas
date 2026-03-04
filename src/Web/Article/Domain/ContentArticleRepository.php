<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Domain;

interface ContentArticleRepository
{
    public function save(ContentArticle $article): void;

    public function search(array $criteria): array; // Returns ContentArticle[]

    /** @return ContentArticle[] */
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;

    public function findById(int $id): ?ContentArticle;

    public function findBySlug(string $slug, string $locale): ?ContentArticle;

    public function remove(int $id): void;
}
