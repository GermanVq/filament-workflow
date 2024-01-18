<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkflowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_action_id',
        'workflow_step_id',
        'user_actor',
        'data',
        'associate_type',
        'associate_id',
        'next_user_responsible'
    ];

    public function workflowAction(): BelongsTo
    {
        return $this->belongsTo(WorkflowAction::class);
    }

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function userActor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_actor');
    }

    public function associate(): MorphTo
    {
        return $this->morphTo();
    }

    public function nextUserResponsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'next_user_responsible');
    }
}
