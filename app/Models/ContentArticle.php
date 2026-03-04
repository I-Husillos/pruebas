<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentArticle extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'title' => 'array', // Translatable JSON
        'slug' => 'array',
        'excerpt' => 'array',
        'content' => 'array',
        'featured_image' => 'array',
        'tags' => 'array',
        'available_markets' => 'array',
        'meta_seo' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class);
    }
}
