<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $guarded = [];

    protected $casts = [
        'blocks_json' => 'array',
        'is_active' => 'boolean',
        'publish_at' => 'datetime',
        'unpublish_at' => 'datetime',
    ];
}
