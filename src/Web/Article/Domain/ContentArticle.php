<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class ContentArticle extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private ?int $articleCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
        private ?string $createdAt,
        private ?string $updatedAt,
        private ?string $deletedAt,
        // ... other fields matching partial DB
    ) {}

    public static function create(
        ?int $articleCategoryId,
        string $status,
        array $images,
        array $localizations
    ): self {
        return new self(
            null,
            $articleCategoryId,
            $status,
            $images,
            $localizations,
            null,
            null,
            null
        );
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['article_category_id'] ?? null,
            $data['status'],
            $data['images'] ?? [],
            $data['localizations'] ?? [],
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['deleted_at'] ?? null
        );
    }

    public function toPrimitives(): array
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

    //
    public function id(): ?int
    {
        return $this->id;
    }
    public function articleCategoryId(): ?int
    {
        return $this->articleCategoryId;
    }
    public function status(): string
    {
        return $this->status;
    }
    public function images(): array
    {
        return $this->images;
    }
    public function localizations(): array
    {
        return $this->localizations;
    }
    public function createdAt(): ?string
    {
        return $this->createdAt;
    }
    public function updatedAt(): ?string
    {
        return $this->updatedAt;
    }
    public function deletedAt(): ?string
    {
        return $this->deletedAt;
    }
}
