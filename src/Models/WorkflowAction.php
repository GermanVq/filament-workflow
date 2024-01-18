<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'workflow_step_id',
        'next_workflow_step_name',
        'data',
        'description',
        'color',
        'icon',
        'label'
    ];

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function nextWorkflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'next_workflow_step_name', 'name');
    }


}
