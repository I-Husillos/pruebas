<?php

namespace Termosalud\Web\Page\Domain;

interface PageRepository
{
    public function save(Page $page): Page;

    public function findBySlug(string $market, string $lang, string $slug): ?Page;

    public function findById(int $id): ?Page;

    public function update(Page $page): void;

    public function delete(int $id): void;

    /** @return Page[] */
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;
}
