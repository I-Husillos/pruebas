<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetZone extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'active' => 'boolean',
    ];

    public function widgets()
    {
        return $this->hasMany(Widget::class, 'zone_id')->where('active', true)->orderBy('sort_order');
    }
}
