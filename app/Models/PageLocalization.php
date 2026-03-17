<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLocalization extends Model
{
    protected $fillable = [
        'slug',
        'page_id',
        'language_id',
        'market_id',
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

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}
