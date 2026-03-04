<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Approvable;

/**
 * Class Treatment
 * 
 * Represents a medical treatment service.
 * 
 * @property int $id
 * @property array $name
 * @property array $slug
 * @property array|null $description
 * @property bool $published
 * @property array|null $available_markets
 * @property int|null $sort_order
 * @property int|null $category_id
 * @property array|null $blocks_json
 * @property array|null $images
 * @property array|null $meta_title
 * @property array|null $meta_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * 
 * @package App\Models
 */
class Treatment extends Model
{
    use HasFactory, SoftDeletes, Approvable;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'indications' => 'array',
        'contraindications' => 'array',
        'procedure_details' => 'array',
        'images' => 'array',
        'related_products' => 'array',
        'published' => 'boolean',
        'published_at' => 'datetime',
        'available_markets' => 'array',
        'meta_seo' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'blocks_json' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(TreatmentCategory::class);
    }
}
