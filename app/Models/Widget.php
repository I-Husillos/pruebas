<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'zone_id',
        'type',
        'title',
        'config',
        'sort_order',
        'active',
        'visibility_rules',
    ];

    protected $casts = [
        'title' => 'array',
        'config' => 'array',
        'visibility_rules' => 'array',
        'active' => 'boolean',
    ];

    public function zone()
    {
        return $this->belongsTo(WidgetZone::class, 'zone_id');
    }

    public function getConfigData($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}
