<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductCategoryTranslation::class, 'product_category_id');
    }
}
