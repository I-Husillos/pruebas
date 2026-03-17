<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = ['status'];

    protected $casts = [
        'status' => 'string',
    ];

    public function localizations(): HasMany
    {
        return $this->hasMany(PageLocalization::class, 'page_id');
    }
}
