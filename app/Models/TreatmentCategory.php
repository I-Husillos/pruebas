<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentCategory extends Model
{
    protected $fillable = [
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'treatment_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TreatmentCategoryTranslation::class, 'treatment_category_id');
    }
}
