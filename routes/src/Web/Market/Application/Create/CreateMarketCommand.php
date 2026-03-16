<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateMarketCommand implements Command
{
    public function __construct(
        private string $code,
        private string $name,
        private string $region,
        private string $defaultLanguage,
        private array $enabledLanguages,
        private bool $active,
        private int $priority
    ) {}

    public function code(): string
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

    public function active(): bool
    {
        return $this->active;
    }

    public function priority(): int
    {
        return $this->priority;
    }
}