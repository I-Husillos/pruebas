<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class ContentMaster extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private string $entityType,
        private int $entityId,
        private string $language, // 'es', 'en'
        private ?string $market, // 'mx', 'us' or NULL
        private string $title,
        private ?string $slug,
        private ?string $body,
        private ?array $metadata
    ) {}

    public static function create(
        string $entityType,
        int $entityId,
        string $language,
        string $title,
        ?string $market = null,
        ?string $slug = null,
        ?string $body = null,
        ?array $metadata = null
    ): self {
        return new self(
            null,
            $entityType,
            $entityId,
            $language,
            $market,
            $title,
            $slug,
            $body,
            $metadata
        );
    }

    // Domain Logic regarding overrides could go here
    public function isOverride(): bool
    {
        return $this->market !== null;
    }
}
