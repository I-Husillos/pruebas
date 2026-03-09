<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateArticleContentCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly string $type,
        private readonly array $title,
        private readonly array $slug,
        private readonly ?array $excerpt,
        private readonly ?array $content,
        private readonly ?string $author,
        private readonly bool $published,
        private readonly ?int $categoryId = null,
        private readonly ?\DateTimeImmutable $publishedAt = null
    ) {}

    public function id(): int
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

    public function categoryId(): ?int
    {
        return $this->categoryId;
    }

    public function publishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}