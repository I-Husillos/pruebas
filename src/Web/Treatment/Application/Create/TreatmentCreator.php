<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Create;

use Termosalud\Web\Treatment\Domain\Treatment;
use Termosalud\Web\Treatment\Domain\TreatmentCategoryId;
use Termosalud\Web\Treatment\Domain\TreatmentId;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class TreatmentCreator
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(
        array $name,
        array $slug,
        ?array $description,
        bool $published,
        ?array $availableMarkets,
        int $sortOrder,
        ?int $categoryId,
        ?array $blocksJson
    ): void {
        $treatment = Treatment::create(
            0,
            $name,
            $slug,
            $description,
            null,
            null,
            null,
            null,
            null,
            $published,
            null,
            $availableMarkets,
            null,
            null,
            null,
            $blocksJson,
            $sortOrder,
            $categoryId !== null ? new TreatmentCategoryId($categoryId) : null
        );

        $this->repository->save($treatment);
    }
}
