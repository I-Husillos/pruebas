<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Language extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $code,
        private string $name,
        private string $nativeName,
        private string $direction,
        private bool $active,
        private ?string $fallbackLanguage = null
    ) {}

    public static function create(
        string $code,
        string $name,
        string $nativeName,
        string $direction = 'ltr',
        bool $active = true,
        ?string $fallbackLanguage = null
    ): self {
        return new self(null, $code, $name, $nativeName, $direction, $active, $fallbackLanguage);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function nativeName(): string
    {
        return $this->nativeName;
    }

    public function direction(): string
    {
        return $this->direction;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function fallbackLanguage(): ?string
    {
        return $this->fallbackLanguage;
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['code'],
            (string) $data['name'],
            (string) $data['native_name'],
            (string) $data['direction'],
            (bool) $data['active'],
            isset($data['fallback_language']) ? (string) $data['fallback_language'] : null
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'native_name' => $this->nativeName,
            'direction' => $this->direction,
            'active' => $this->active,
            'fallback_language' => $this->fallbackLanguage,
        ];
    }
}
