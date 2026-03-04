<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Article\Domain\ContentArticle;

final class ArticleResponse implements Response
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
        private readonly ?string $publishedAt
    ) {}

    public static function fromArticle(ContentArticle $article): self
    {
        return new self(
            $article->id(),
            $article->type(),
            $article->title(),
            $article->slug(),
            $article->excerpt(),
            $article->content(),
            $article->author(),
            $article->published(),
            $article->publishedAt() ? $article->publishedAt()->format('Y-m-d H:i:s') : null
        );
    }

    public function toArray(): array
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
            'published_at' => $this->publishedAt,
        ];
    }
}
