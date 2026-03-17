<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface PageRepository
{
    public function save(Page $page): void;

    public function search(int $id): ?Page;

    public function findBySlug(string $slug, int $languageId, int $marketId): ?Page;

    public function remove(int $id): void;

    public function removeLocalization(int $localizationId): void;

    /** @return Page[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
