<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Product extends AggregateRoot
{
    private int $id;

    private ProductCode $code;

    private array $name;

    private array $slug;

    private ?array $shortDescription;

    private ?array $description;

    private ?array $technicalSpecs;

    private ?array $images;

    private ?int $categoryId;

    private ?array $categoryName;

    private ?array $tags;

    private bool $published;

    private ?string $publishedAt;

    private ?array $availableMarkets;

    private ?array $metaSeo;

    private int $sortOrder;

    private ?string $createdAt;

    private ?string $updatedAt;

    public function __construct(
        int $id,
        ProductCode $code,
        array $name,
        array $slug,
        ?array $shortDescription,
        ?array $description,
        ?array $technicalSpecs,
        ?array $images,
        ?int $categoryId,
        ?array $categoryName,
        ?array $tags,
        bool $published,
        ?string $publishedAt,
        ?array $availableMarkets,
        ?array $metaSeo,
        int $sortOrder,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->slug = $slug;
        $this->shortDescription = $shortDescription;
        $this->description = $description;
        $this->technicalSpecs = $technicalSpecs;
        $this->images = $images;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->tags = $tags;
        $this->published = $published;
        $this->publishedAt = $publishedAt;
        $this->availableMarkets = $availableMarkets;
        $this->metaSeo = $metaSeo;
        $this->sortOrder = $sortOrder;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        int $id,
        ProductCode $code,
        array $name,
        array $slug,
        ?array $shortDescription = null,
        ?array $description = null,
        ?array $technicalSpecs = null,
        ?array $images = null,
        ?int $categoryId = null,
        ?array $categoryName = null,
        ?array $tags = null,
        bool $published = false,
        ?string $publishedAt = null,
        ?array $availableMarkets = null,
        ?array $metaSeo = null,
        int $sortOrder = 0
    ): self {
        return new self(
            $id,
            $code,
            $name,
            $slug,
            $shortDescription,
            $description,
            $technicalSpecs,
            $images,
            $categoryId,
            $categoryName,
            $tags,
            $published,
            $publishedAt,
            $availableMarkets,
            $metaSeo,
            $sortOrder
        );
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            (int) $data['id'],
            new ProductCode($data['code']),
            $data['name'],
            $data['slug'],
            $data['short_description'] ?? null,
            $data['description'] ?? null,
            $data['technical_specs'] ?? null,
            $data['images'] ?? null,
            isset($data['category_id'])
                ? (int) $data['category_id']
                : (isset($data['category']) ? (int) $data['category'] : null),
            $data['category_name'] ?? null,
            $data['tags'] ?? null,
            (bool) ($data['published'] ?? false),
            $data['published_at'] ?? null,
            $data['available_markets'] ?? null,
            $data['meta_seo'] ?? null,
            (int) ($data['sort_order'] ?? 0),
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code->value(),
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'technical_specs' => $this->technicalSpecs,
            'images' => $this->images,
            'category_id' => $this->categoryId,
            'category_name' => $this->categoryName,
            'tags' => $this->tags,
            'published' => $this->published,
            'published_at' => $this->publishedAt,
            'available_markets' => $this->availableMarkets,
            'meta_seo' => $this->metaSeo,
            'sort_order' => $this->sortOrder,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
