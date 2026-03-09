<?php

namespace Termosalud\Web\Page\Domain;

class Page
{
    public $id;

    public $marketCode;

    public $languageCode;

    public $slug;

    public $isActive;

    public $seoTitle;

    public $seoDescription;

    public $blocks;

    public function __construct(
        ?int $id,
        string $marketCode,
        string $languageCode,
        string $slug,
        bool $isActive,
        ?string $seoTitle,
        ?string $seoDescription,
        array $blocks
    ) {
        $this->id = $id;
        $this->marketCode = $marketCode;
        $this->languageCode = $languageCode;
        $this->slug = $slug;
        $this->isActive = $isActive;
        $this->seoTitle = $seoTitle;
        $this->seoDescription = $seoDescription;
        $this->blocks = $blocks;
    }

    public static function create(
        string $marketCode,
        string $languageCode,
        string $slug,
        bool $isActive,
        ?string $seoTitle,
        ?string $seoDescription,
        array $blocks
    ): self {
        return new self(
            null,
            $marketCode,
            $languageCode,
            $slug,
            $isActive,
            $seoTitle,
            $seoDescription,
            $blocks
        );
    }

    public static function fromPrimitives(
        ?int $id,
        string $marketCode,
        string $languageCode,
        string $slug,
        bool $isActive,
        ?string $seoTitle,
        ?string $seoDescription,
        array $blocks
    ): self {
        return new self(
            $id,
            $marketCode,
            $languageCode,
            $slug,
            $isActive,
            $seoTitle,
            $seoDescription,
            $blocks
        );
    }


    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'market_code' => $this->marketCode,
            'language_code' => $this->languageCode,
            'slug' => $this->slug,
            'is_active' => $this->isActive,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
            'blocks' => $this->blocks,
        ];
    }


}
