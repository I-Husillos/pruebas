<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    protected $fillable = [
        'code',
        'name',
        'region',
        'default_language',
        'enabled_languages',
        'active',
        'priority',
    ];

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'region' => 'string',
        'default_language' => 'string',
        'enabled_languages' => 'array',
        'active' => 'boolean',
        'priority' => 'integer',
    ];

    public function defaultLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'default_language', 'code');
    }

    public function productLocalizations(): HasMany
    {
        return $this->hasMany(ProductLocalization::class, 'market_id');
    }

    public function articleLocalizations(): HasMany
    {
        return $this->hasMany(ArticleLocalization::class, 'market_id');
    }

    public function treatmentLocalizations(): HasMany
    {
        return $this->hasMany(TreatmentLocalization::class, 'market_id');
    }
}
