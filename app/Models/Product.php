<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Approvable;

/**
 * Class Product
 *
 * @property int $id
 * @property string $code
 * @property array $name
 * @property array $slug
 * @property array|null $description
 * @property bool $published
 * @property array|null $available_markets
 * @property int|null $category_id
 * @property array|null $blocks_json
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Product extends Model
{
    use HasFactory, SoftDeletes, Approvable;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'short_description',
        'description',
        'technical_specs',
        'images',
        'category',
        'category_id',
        'tags',
        'published',
        'published_at',
        'available_markets',
        'meta_seo',
        'meta_title',
        'meta_description',
        'sort_order',
        'blocks_json',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'short_description' => 'array',
        'description' => 'array',
        'technical_specs' => 'array',
        'images' => 'array',
        'tags' => 'array',
        'available_markets' => 'array',
        'meta_seo' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'published' => 'boolean',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
        'blocks_json' => 'array',
    ];
}
