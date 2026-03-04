<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Treatment\Domain\Treatment;

final class TreatmentResponse implements Response
{
    public function __construct(
        private readonly int $id,
        private readonly array $name,
        private readonly array $slug,
        private readonly ?array $description,
        private readonly ?array $indications,
        private readonly ?array $contraindications,
        private readonly ?array $procedureDetails,
        private readonly ?array $images,
        private readonly ?array $relatedProducts,
        private readonly bool $published,
        private readonly ?string $publishedAt,
        private readonly ?array $availableMarkets,
        private readonly ?array $metaSeo,
        private readonly ?array $metaTitle,
        private readonly ?array $metaDescription,
        private readonly ?array $blocksJson,
        private readonly int $sortOrder,
        private readonly ?int $categoryId,
        private readonly ?string $createdAt,
        private readonly ?string $updatedAt
    ) {}

    public static function fromTreatment(Treatment $treatment): self
    {
        $primitives = $treatment->toPrimitives();

        return new self(
            $primitives['id'],
            $primitives['name'],
            $primitives['slug'],
            $primitives['description'],
            $primitives['indications'],
            $primitives['contraindications'],
            $primitives['procedure_details'] ?? null,
            $primitives['images'],
            $primitives['related_products'] ?? null,
            $primitives['published'],
            $primitives['published_at'],
            $primitives['available_markets'],
            $primitives['meta_seo'],
            $primitives['meta_title'],
            $primitives['meta_description'],
            $primitives['blocks_json'],
            $primitives['sort_order'],
            $primitives['category_id'],
            $primitives['created_at'],
            $primitives['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'indications' => $this->indications,
            'contraindications' => $this->contraindications,
            'procedure_details' => $this->procedureDetails,
            'images' => $this->images,
            'related_products' => $this->relatedProducts,
            'published' => $this->published,
            'published_at' => $this->publishedAt,
            'available_markets' => $this->availableMarkets,
            'meta_seo' => $this->metaSeo,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'blocks_json' => $this->blocksJson,
            'sort_order' => $this->sortOrder,
            'category_id' => $this->categoryId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
