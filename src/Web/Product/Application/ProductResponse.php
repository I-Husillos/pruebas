<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Product\Domain\Product;

final class ProductResponse implements Response
{
    private string $id;

    private string $code;

    private array $name;

    private array $slug;

    private ?array $shortDescription;

    private ?array $description;

    private ?array $technicalSpecs;

    private ?array $images;

    private ?string $category;

    private ?array $tags;

    private bool $published;

    private ?string $publishedAt;

    private ?array $availableMarkets;

    private ?array $metaSeo;

    private int $sortOrder;

    private ?string $createdAt;

    private ?string $updatedAt;

    public function __construct(
        string $id,
        string $code,
        array $name,
        array $slug,
        ?array $shortDescription,
        ?array $description,
        ?array $technicalSpecs,
        ?array $images,
        ?string $category,
        ?array $tags,
        bool $published,
        ?string $publishedAt,
        ?array $availableMarkets,
        ?array $metaSeo,
        int $sortOrder,
        ?string $createdAt,
        ?string $updatedAt
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->slug = $slug;
        $this->shortDescription = $shortDescription;
        $this->description = $description;
        $this->technicalSpecs = $technicalSpecs;
        $this->images = $images;
        $this->category = $category;
        $this->tags = $tags;
        $this->published = $published;
        $this->publishedAt = $publishedAt;
        $this->availableMarkets = $availableMarkets;
        $this->metaSeo = $metaSeo;
        $this->sortOrder = $sortOrder;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromProduct(Product $product): self
    {
        $primitives = $product->toPrimitives();

        return new self(
            (string) $primitives['id'],
            $primitives['code'],
            $primitives['name'],
            $primitives['slug'],
            $primitives['short_description'] ?? null,
            $primitives['description'] ?? null,
            $primitives['technical_specs'] ?? null,
            $primitives['images'] ?? null,
            $primitives['category'] ?? null,
            $primitives['tags'] ?? null,
            (bool) $primitives['published'],
            $primitives['published_at'] ?? null,
            $primitives['available_markets'] ?? null,
            $primitives['meta_seo'] ?? null,
            (int) ($primitives['sort_order'] ?? 0),
            $primitives['created_at'] ?? null,
            $primitives['updated_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'technical_specs' => $this->technicalSpecs,
            'images' => $this->images,
            'category' => $this->category,
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
