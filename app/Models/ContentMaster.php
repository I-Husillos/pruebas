<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMaster extends Model
{
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
