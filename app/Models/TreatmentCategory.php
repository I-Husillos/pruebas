<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentCategory extends Model
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

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'category_id');
    }
}
