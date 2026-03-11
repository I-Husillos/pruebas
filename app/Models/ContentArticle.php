<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentArticle extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'article_category_id',
        'status',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function localizations(): HasMany
    {
        return $this->hasMany(ArticleLocalization::class, 'article_id');
    }

    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['article_category_id'] ?? null,
            set: fn ($value) => ['article_category_id' => $value]
        );
    }

    protected function published(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->attributes['status'] ?? 'draft') === 'published',
            set: fn ($value) => ['status' => $value ? 'published' : 'draft']
        );
    }
}
