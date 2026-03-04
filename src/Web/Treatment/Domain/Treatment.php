<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Treatment extends AggregateRoot
{
    private TreatmentId $id;

    private array $name;

    private array $slug;

    private ?array $description;

    private ?array $indications;

    private ?array $contraindications;

    private ?array $procedureDetails;

    private ?array $images;

    private ?array $relatedProducts;

    private bool $published;

    private ?string $publishedAt;

    private ?array $availableMarkets;

    private ?array $metaSeo;

    private ?array $metaTitle;

    private ?array $metaDescription;

    private ?array $blocksJson;

    private int $sortOrder;

    private ?TreatmentCategoryId $categoryId;

    private ?string $createdAt;

    private ?string $updatedAt;

    public function __construct(
        TreatmentId $id,
        array $name,
        array $slug,
        ?array $description,
        ?array $indications,
        ?array $contraindications,
        ?array $procedureDetails,
        ?array $images,
        ?array $relatedProducts,
        bool $published,
        ?string $publishedAt,
        ?array $availableMarkets,
        ?array $metaSeo,
        ?array $metaTitle,
        ?array $metaDescription,
        ?array $blocksJson,
        int $sortOrder,
        ?TreatmentCategoryId $categoryId,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->indications = $indications;
        $this->contraindications = $contraindications;
        $this->procedureDetails = $procedureDetails;
        $this->images = $images;
        $this->relatedProducts = $relatedProducts;
        $this->published = $published;
        $this->publishedAt = $publishedAt;
        $this->availableMarkets = $availableMarkets;
        $this->metaSeo = $metaSeo;
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->blocksJson = $blocksJson;
        $this->sortOrder = $sortOrder;
        $this->categoryId = $categoryId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        TreatmentId $id,
        array $name,
        array $slug,
        ?array $description = null,
        ?array $indications = null,
        ?array $contraindications = null,
        ?array $procedureDetails = null,
        ?array $images = null,
        ?array $relatedProducts = null,
        bool $published = false,
        ?string $publishedAt = null,
        ?array $availableMarkets = null,
        ?array $metaSeo = null,
        ?array $metaTitle = null,
        ?array $metaDescription = null,
        ?array $blocksJson = null,
        int $sortOrder = 0,
        ?TreatmentCategoryId $categoryId = null
    ): self {
        return new self(
            $id,
            $name,
            $slug,
            $description,
            $indications,
            $contraindications,
            $procedureDetails,
            $images,
            $relatedProducts,
            $published,
            $publishedAt,
            $availableMarkets,
            $metaSeo,
            $metaTitle,
            $metaDescription,
            $blocksJson,
            $sortOrder,
            $categoryId
        );
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            new TreatmentId((int) $data['id']),
            $data['name'] ?? [],
            $data['slug'] ?? [],
            $data['description'] ?? null,
            $data['indications'] ?? null,
            $data['contraindications'] ?? null,
            $data['procedure_details'] ?? null,
            $data['images'] ?? null,
            $data['related_products'] ?? null,
            (bool) ($data['published'] ?? false),
            $data['published_at'] ?? null,
            $data['available_markets'] ?? null,
            $data['meta_seo'] ?? null,
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null,
            $data['blocks_json'] ?? null,
            (int) ($data['sort_order'] ?? 0),
            isset($data['category_id']) ? new TreatmentCategoryId((int) $data['category_id']) : null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
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
            'category_id' => $this->categoryId ? $this->categoryId->value() : null,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
