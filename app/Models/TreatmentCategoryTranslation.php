<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentCategoryTranslation extends Model
{
    protected $fillable = [
        'treatment_category_id',
        'language_id',
        'title',
        'description',
        'slug',
        'seo_metadata',
    ];

    protected $casts = [
        'seo_metadata' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TreatmentCategory::class, 'treatment_category_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
