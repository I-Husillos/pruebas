<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateContentArticleCommand implements Command
{
    public function __construct(
        private readonly ?int $id,
        private string $type,
        private array $title,
        private array $slug,
        private ?array $excerpt,
        private ?array $content,
        private ?string $author,
        private bool $published,
        private ?\DateTimeImmutable $publishedAt,
    ) {}

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
