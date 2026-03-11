<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleLocalization extends Model
{
    protected $fillable = [
        'article_id',
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

    public function article(): BelongsTo
    {
        return $this->belongsTo(ContentArticle::class, 'article_id');
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
