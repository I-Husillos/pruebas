<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Market extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private readonly MarketCode $code,
        private string $name,
        private string $region,
        private string $defaultLanguage,
        private array $enabledLanguages,
        private bool $active,
        private int $priority
    ) {}

    public static function create(
        MarketCode $code,
        string $name,
        string $region,
        string $defaultLanguage,
        array $enabledLanguages,
        bool $active = true,
        int $priority = 0
    ): self {
        return new self(null, $code, $name, $region, $defaultLanguage, $enabledLanguages, $active, $priority);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function code(): MarketCode
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function defaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    public function enabledLanguages(): array
    {
        return $this->enabledLanguages;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function priority(): int
    {
        return $this->priority;
    }
}
