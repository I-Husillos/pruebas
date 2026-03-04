<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled_languages' => 'array',
        'active' => 'boolean',
        'status' => 'boolean',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'market_languages')
            ->withPivot('is_default', 'is_active');
    }
}
