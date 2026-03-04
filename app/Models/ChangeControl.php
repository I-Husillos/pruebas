<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeControl extends Model
{
    const STATUS_DRAFT = 'draft';

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_IMPLEMENTED = 'implemented';

    protected $fillable = [
        'title',
        'description',
        'status',
        'requester_id',
        'reason',
        'impact',
        'approval_date',
        'implementation_date',
        'changeable_type',
        'changeable_id',
        'payload',
        'type',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
        'implementation_date' => 'datetime',
        'payload' => 'array',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function changeable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $transitions = [
            self::STATUS_DRAFT => [self::STATUS_PENDING],
            self::STATUS_PENDING => [self::STATUS_DRAFT, self::STATUS_APPROVED, 'rejected'],
            self::STATUS_APPROVED => [self::STATUS_PENDING, self::STATUS_IMPLEMENTED],
            self::STATUS_IMPLEMENTED => [],
            'rejected' => [self::STATUS_DRAFT, self::STATUS_PENDING],
        ];

        return in_array($newStatus, $transitions[$this->status] ?? []);
    }
}
