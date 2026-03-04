<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'route_name',
        'route_params',
        'icon',
        'target',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'label' => 'array',
        'route_params' => 'array',
        'active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function getUrl($lang, $market = 'es')
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route_name) {
            $params = $this->route_params ?? [];
            $params['market'] = $market;
            $params['lang'] = $lang;

            try {
                return route($this->route_name, $params);
            } catch (\Exception $e) {
                return '#';
            }
        }

        return '#';
    }
}
