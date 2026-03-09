<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface LanguageRepository
{
    public function save(Language $language): void;

    public function search(int $id): ?Language;

    public function remove(int $id): void;

    public function findByCode(string $code): ?Language;

    public function findAllActive(): array;

    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
