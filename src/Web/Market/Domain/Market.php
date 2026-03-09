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

    public static function fromPrimitives(array $data): self
    {
        $enabledLanguages = $data['enabled_languages'] ?? [];

        if (is_string($enabledLanguages)) {
            $enabledLanguages = json_decode($enabledLanguages, true) ?? [];
        }

        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            new MarketCode((string) $data['code']),
            (string) $data['name'],
            (string) $data['region'],
            (string) $data['default_language'],
            is_array($enabledLanguages) ? $enabledLanguages : [],
            (bool) $data['active'],
            (int) $data['priority']
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code->value(),
            'name' => $this->name,
            'region' => $this->region,
            'default_language' => $this->defaultLanguage,
            'enabled_languages' => $this->enabledLanguages,
            'active' => $this->active,
            'priority' => $this->priority,
        ];
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
