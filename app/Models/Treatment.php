<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Approvable;

/**
 * Class Treatment
 * 
 * Represents a medical treatment service.
 * 
 * @property int $id
 * @property int|null $treatment_category_id
 * @property string $status
 * @property array|null $images
 * @property array|null $related_products
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * 
 * @package App\Models
 */
class Treatment extends Model
{
    use HasFactory, SoftDeletes, Approvable;

    protected $fillable = [
        'treatment_category_id',
        'status',
        'images',
        'related_products',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'related_products' => 'array',
        'order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TreatmentCategory::class, 'treatment_category_id');
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(TreatmentLocalization::class, 'treatment_id');
    }

    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['treatment_category_id'] ?? null,
            set: fn ($value) => ['treatment_category_id' => $value]
        );
    }
}
