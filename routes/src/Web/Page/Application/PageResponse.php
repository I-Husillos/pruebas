<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Page\Domain\Page;

final class PageResponse implements Response
{
    private int $id;
    private string $marketCode;
    private string $languageCode;
    private string $slug;
    private bool $isActive;
    private ?string $seoTitle;
    private ?string $seoDescription;
    private array $blocks;

    public function __construct(
        int $id,
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

    public static function fromPage(Page $page): self
    {
        return new self(
            $page->id,
            $page->marketCode,
            $page->languageCode,
            $page->slug,
            $page->isActive,
            $page->seoTitle,
            $page->seoDescription,
            $page->blocks
        );
    }

    public function toArray(): array
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
