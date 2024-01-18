<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'workflow_id',
        'user_assigned',
        'role_assigned',
        'description',
        'color',

    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function workflowActions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class);
    }

    public function userAssigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_assigned');
    }

    public function roleAssigned(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_assigned');
    }

    public function nextStepOfAction(): HasOne
    {
        return $this->hasOne(WorkflowAction::class, 'next_workflow_step_name', 'name');
    }


}
