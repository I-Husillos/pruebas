<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Page\Domain\Page;

final class PageResponse implements Response
{
    public function __construct(
        private readonly ?int   $id,
        private readonly string $status,
        private readonly array  $localizations,
        private readonly ?string $createdAt,
        private readonly ?string $updatedAt,
        private readonly ?string $deletedAt,
    ) {}

    public static function fromPage(Page $page): self
    {
        return new self(
            $page->id(),
            $page->status(),
            $page->localizations(),
            $page->createdAt(),
            $page->updatedAt(),
            $page->deletedAt(),
        );
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'status'        => $this->status,
            'localizations' => $this->localizations,
            'created_at'    => $this->createdAt,
            'updated_at'    => $this->updatedAt,
            'deleted_at'    => $this->deletedAt,
        ];
    }
}
