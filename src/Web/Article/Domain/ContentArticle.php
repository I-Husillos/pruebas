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
        private ?int $category,
        private ?\DateTimeImmutable $publishedAt,
        // ... other fields matching partial DB
    ) {}

    public static function create(
        string $type,
        array $title,
        array $slug,
        ?array $excerpt = null,
        ?array $content = null,
        ?string $author = null,
        ?int $categoryId = null,
        ?\DateTimeImmutable $publishedAt = null
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
            $categoryId,
            $publishedAt
        );
    }

    public static function fromPrimitives(array $data): self
    {
        $publishedAt = null;
        $rawPublishedAt = $data['published_at'] ?? null;

        if ($rawPublishedAt instanceof \DateTimeInterface) {
            $publishedAt = \DateTimeImmutable::createFromInterface($rawPublishedAt);
        } elseif (is_string($rawPublishedAt) && $rawPublishedAt !== '') {
            $publishedAt = new \DateTimeImmutable($rawPublishedAt);
        }

        return new self(
            isset($data['id']) ? (int) $data['id'] : null,
            (string) $data['type'],
            $data['title'] ?? [],
            $data['slug'] ?? [],
            $data['excerpt'] ?? null,
            $data['content'] ?? null,
            $data['author'] ?? null,
            (bool) ($data['published'] ?? false),
            isset($data['category_id'])
                ? (int) $data['category_id']
                : (isset($data['category']) ? (int) $data['category'] : null),
            $publishedAt
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'author' => $this->author,
            'published' => $this->published,
            'category_id' => $this->category,
            'published_at' => $this->publishedAt?->format('Y-m-d H:i:s'),
        ];
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

    public function category(): ?int
    {
        return $this->category;
    }

    public function publishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
