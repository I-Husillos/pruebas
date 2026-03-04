<?php

namespace App\Traits;

use App\Models\ChangeControl;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Approvable
{
    /**
     * Get all change controls for this model.
     */
    public function changeControls(): MorphMany
    {
        return $this->morphMany(ChangeControl::class, 'changeable')->orderByDesc('created_at');
    }

    /**
     * Get the latest pending change control.
     */
    public function pendingChange(): MorphOne
    {
        return $this->morphOne(ChangeControl::class, 'changeable')
            ->where('status', ChangeControl::STATUS_PENDING)
            ->latestOfMany();
    }
}
