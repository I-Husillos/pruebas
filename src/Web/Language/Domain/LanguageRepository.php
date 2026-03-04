<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Domain;

interface LanguageRepository
{
    public function save(Language $language): void;

    public function findByCode(string $code): ?Language;

    public function findAllActive(): array;

    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;
}
