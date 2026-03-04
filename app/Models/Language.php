<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Check migrations: 'code' is string primary-ish but ID is int.
    // Usually standard models work fine.
}
