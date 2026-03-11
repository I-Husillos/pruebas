<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends Model
{
    protected $fillable = [
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(ContentArticle::class, 'article_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ArticleCategoryTranslation::class, 'article_category_id');
    }
}
