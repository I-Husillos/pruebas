<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class ContentArticle extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private string $type, // blog, news, press
        private array $title, // localized json
        private array $slug, // localized json
        private ?array $excerpt, // localized json
        private ?array $content, // localized json
        private ?string $author,
        private bool $published,
        private ?\DateTimeImmutable $publishedAt,
        // ... other fields matching partial DB
    ) {}

    public static function create(
        string $type,
        array $title,
        array $slug,
        ?array $excerpt = null,
        ?array $content = null,
        ?string $author = null
    ): self {
        return new self(
            null,
            $type,
            $title,
            $slug,
            $excerpt,
            $content,
            $author,
            false,
            null
        );
    }

    // Getters...
    public function id(): ?int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): array
    {
        return $this->title;
    }

    public function slug(): array
    {
        return $this->slug;
    }

    public function excerpt(): ?array
    {
        return $this->excerpt;
    }

    public function content(): ?array
    {
        return $this->content;
    }

    public function author(): ?string
    {
        return $this->author;
    }

    public function published(): bool
    {
        return $this->published;
    }

    public function publishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
