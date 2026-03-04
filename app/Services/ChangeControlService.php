<?php

namespace App\Services;

use App\Models\ChangeControl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChangeControlService
{
    /**
     * Create a new draft/pending change for a model.
     *
     * @param Model $model The model instance (can be new instance if create)
     * @param array $data The data to be changed (payload)
     * @param string $type Change type: 'create', 'update', 'delete'
     * @return ChangeControl
     */
    public function createChangeRequest(Model $model, array $data, string $type = 'update'): ChangeControl
    {
        // If type is create, model exists only in memory or we store class name
        $changeableType = get_class($model);
        $changeableId = $model->exists ? $model->id : null;

        $changeControl = ChangeControl::create([
            'title' => ucfirst($type) . " request for " . class_basename($model) . ($changeableId ? " #$changeableId" : ""),
            'description' => "Request to $type " . class_basename($model),
            'status' => ChangeControl::STATUS_PENDING,
            'requester_id' => Auth::id(),
            'changeable_type' => $changeableType,
            'changeable_id' => $changeableId,
            'payload' => $data,
            'type' => $type,
        ]);

        // Notify Quality/Admin team
        $this->notifyTeam($changeControl);

        return $changeControl;
    }

    protected function notifyTeam(ChangeControl $changeControl): void
    {
        $users = \App\Models\User::role(['super-admin', 'quality'])->get();
        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ChangeRequestCreatedNotification($changeControl));
        }
    }

    /**
     * Approve a change request and apply it.
     */
    public function approve(ChangeControl $changeControl): void
    {
        if ($changeControl->type === 'update') {
            $class = $changeControl->changeable_type;
            $model = $class::findOrFail($changeControl->changeable_id);

            // Apply payload
            $model->update($changeControl->payload);
        } elseif ($changeControl->type === 'create') {
            $class = $changeControl->changeable_type;
            // Create model
            $model = $class::create($changeControl->payload);

            // Link back so we have history? 
            // Or update the ChangeControl to have the ID now
            $changeControl->update(['changeable_id' => $model->id]);
        } elseif ($changeControl->type === 'delete') {
            $class = $changeControl->changeable_type;
            $model = $class::findOrFail($changeControl->changeable_id);
            $model->delete();
        }

        $changeControl->update([
            'status' => ChangeControl::STATUS_APPROVED,
            'approval_date' => now(),
        ]);
    }

    /**
     * Reject a change request.
     */
    public function reject(ChangeControl $changeControl, ?string $reason = null): void
    {
        $changeControl->update([
            'status' => 'rejected',
            'reason' => $reason
        ]);
    }
}
