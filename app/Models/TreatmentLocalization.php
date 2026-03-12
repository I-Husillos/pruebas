<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentLocalization extends Model
{
    protected $fillable = [
        'treatment_id',
        'language_id',
        'market_id',
        'slug',
        'title',
        'excerpt',
        'description',
        'content',
        'indications',
        'contraindications',
        'seo_metadata',
    ];

    protected $casts = [
        'content' => 'array',
        'indications' => 'array',
        'contraindications' => 'array',
        'seo_metadata' => 'array',
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}
