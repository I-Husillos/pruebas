<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Market\Domain\Market;

final class MarketResponse implements Response
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $code,
        private readonly string $name,
        private readonly string $region,
        private readonly string $defaultLanguage,
        private readonly array $enabledLanguages,
        private readonly bool $isActive,
        private readonly int $priority
    ) {}

    public static function fromMarket(Market $market): self
    {
        return new self(
            $market->id(),
            $market->code()->value(),
            $market->name(),
            $market->region(),
            $market->defaultLanguage(),
            $market->enabledLanguages(),
            $market->isActive(),
            $market->priority()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'region' => $this->region,
            'default_language' => $this->defaultLanguage,
            'enabled_languages' => $this->enabledLanguages,
            'is_active' => $this->isActive,
            'priority' => $this->priority,
        ];
    }
}
