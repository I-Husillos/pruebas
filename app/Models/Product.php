<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Approvable;

/**
 * Class Product
 *
 * @property int $id
 * @property string $code
 * @property int|null $product_category_id
 * @property string $status
 * @property array|null $images
 * @property array|null $related_treatments
 * @property int $order
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
        'product_category_id',
        'status',
        'images',
        'related_treatments',
        'order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(ProductLocalization::class, 'product_id');
    }

    protected $casts = [
        'images' => 'array',
        'related_treatments' => 'array',
        'order' => 'integer',
    ];

    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['product_category_id'] ?? null,
            set: fn ($value) => ['product_category_id' => $value]
        );
    }
}
