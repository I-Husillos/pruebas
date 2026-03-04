<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Language\Domain\Language;

final class LanguageResponse implements Response
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $code,
        private readonly string $name,
        private readonly string $nativeName,
        private readonly string $direction,
        private readonly bool $isActive,
        private readonly ?string $fallbackLanguage
    ) {}

    public static function fromLanguage(Language $language): self
    {
        return new self(
            $language->id(),
            $language->code(),
            $language->name(),
            $language->nativeName(),
            $language->direction(),
            $language->isActive(),
            $language->fallbackLanguage()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'native_name' => $this->nativeName,
            'direction' => $this->direction,
            'is_active' => $this->isActive,
            'fallback_language' => $this->fallbackLanguage,
        ];
    }
}
