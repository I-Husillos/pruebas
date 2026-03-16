<?php

namespace Termosalud\Web\Page\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface PageRepository
{
    public function save(Page $page): Page;

    public function search(int $id): ?Page;

    public function findBySlug(string $market, string $lang, string $slug): ?Page;

    public function update(Page $page): void;

    public function remove(int $id): void;

    /** @return Page[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
