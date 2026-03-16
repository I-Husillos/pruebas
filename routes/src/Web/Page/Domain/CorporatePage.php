<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class CorporatePage extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private string $code, // 'about-us'
        private bool $isSharedAcrossMarkets,
        private ?array $restrictedToMarkets
    ) {}

    public static function create(
        string $code,
        bool $isSharedAcrossMarkets = true,
        ?array $restrictedToMarkets = null
    ): self {
        return new self(
            null,
            $code,
            $isSharedAcrossMarkets,
            $restrictedToMarkets
        );
    }
}
