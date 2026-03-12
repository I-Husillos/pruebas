<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Article\Domain\ContentArticle;

final class ArticleResponse implements Response
{
    public function __construct(
        private readonly ?int $id,
        private int $articleCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
        private ?string $createdAt,
        private ?string $updatedAt,
        private ?string $deletedAt,
    ) {}

    public static function fromArticle(ContentArticle $article): self
    {
        return new self(
            $article->id(),
            $article->articleCategoryId(),
            $article->status(),
            $article->images(),
            $article->localizations(),
            $article->createdAt(),
            $article->updatedAt(),
            $article->deletedAt()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'article_category_id' => $this->articleCategoryId,
            'status' => $this->status,
            'images' => $this->images,
            'localizations' => $this->localizations,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'deleted_at' => $this->deletedAt,
        ];
    }
}
