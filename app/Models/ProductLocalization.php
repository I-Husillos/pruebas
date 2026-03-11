<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLocalization extends Model
{
    protected $fillable = [
        'product_id',
        'language_id',
        'market_id',
        'slug',
        'title',
        'excerpt',
        'description',
        'content',
        'seo_metadata',
    ];

    protected $casts = [
        'content' => 'array',
        'seo_metadata' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
