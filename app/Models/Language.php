<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'direction',
        'active',
        'fallback_language',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function fallbackLanguage(): BelongsTo
    {
        return $this->belongsTo(self::class, 'fallback_language', 'code');
    }

    public function fallbackChildren(): HasMany
    {
        return $this->hasMany(self::class, 'fallback_language', 'code');
    }

    public function productLocalizations(): HasMany
    {
        return $this->hasMany(ProductLocalization::class, 'language_id');
    }

    public function articleLocalizations(): HasMany
    {
        return $this->hasMany(ArticleLocalization::class, 'language_id');
    }

    public function treatmentLocalizations(): HasMany
    {
        return $this->hasMany(TreatmentLocalization::class, 'language_id');
    }

    public function productCategoryTranslations(): HasMany
    {
        return $this->hasMany(ProductCategoryTranslation::class, 'language_id');
    }

    public function articleCategoryTranslations(): HasMany
    {
        return $this->hasMany(ArticleCategoryTranslation::class, 'language_id');
    }

    public function treatmentCategoryTranslations(): HasMany
    {
        return $this->hasMany(TreatmentCategoryTranslation::class, 'language_id');
    }
}
