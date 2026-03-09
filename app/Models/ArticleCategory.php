<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'active' => 'boolean',
    ];

    public function articles()
    {
        return $this->hasMany(ContentArticle::class, 'category_id');
    }
}
