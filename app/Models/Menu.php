<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'key',
        'description',
        'active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
