<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateArticleContentCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly string $title,
        private readonly string $slug,
        private readonly string $excerpt,
        private readonly string $content,
        private readonly string $author,
        private readonly bool $published,
        private readonly ?\DateTimeImmutable $publishedAt = null
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function excerpt(): string
    {
        return $this->excerpt;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function author(): string
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