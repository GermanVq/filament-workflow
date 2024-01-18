<?php

namespace App\Logic\Workflow;

use App\Models\WorkflowAction;
use App\Models\WorkflowModel;
use App\Models\WorkflowRecord;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateMachineConfig;
use Bvtterfly\ModelStateMachine\Exceptions\CouldNotFindStateMachineField;
use Bvtterfly\ModelStateMachine\Exceptions\FieldWithoutCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasWorkFlow
{


    public static function bootHasWorkFlow(): void
    {

        static::created(function (Model $model) {
            /**
             * @var HasWorkFlow $model
             */
            $model->setInitialStates();
        });
    }

    public function workflow_model_name(): string
    {
        return class_basename(__CLASS__);
    }

    public function getWorkflowModelAttribute(): WorkflowModel|null
    {
        return WorkflowModel::where('model', get_class())->first();
    }

    private function setInitialStates(): void
    {
        if($this->getWorkflowModelAttribute()){
            $workflow_model = $this->getWorkflowModelAttribute();
            $first_workflow_step = $workflow_model->workflow->workflowSteps()->doesntHave('nextStepOfAction')->first();

            if($first_workflow_step){
                $workflow_step_id = $first_workflow_step->id;
                $saveRecord = new WorkflowRecord;
                $saveRecord->workflow_step_id = $workflow_step_id;
                $saveRecord->user_actor = Auth::user()->id;
                $saveRecord->associate_type = get_class();
                $saveRecord->associate_id = $this->id;
                $saveRecord->save();

            }
        }
    }

    public function latestWorkflowRecord()
    {
        return $this->morphOne(WorkflowRecord::class, 'associate')->latest();
    }


    public function workflowRecord()
    {
        return $this->morphMany(WorkflowRecord::class, 'associate');
    }

    public function transitionTo($data)
    {
        $workflow_action = $data['action'];

        $next_workflow_step = $workflow_action->nextWorkflowStep;
        $actual_workflow_step = $workflow_action->workflowStep;
        $workflow_data = $data;
        unset($workflow_data['action']);

        $workflow_record = new WorkflowRecord;
        $workflow_record->workflow_action_id = $workflow_action->id;
        $workflow_record->workflow_step_id = $next_workflow_step->id ?? $actual_workflow_step->id;
        $workflow_record->user_actor =  Auth::user()->id;
        $workflow_record->data = !empty($workflow_data) ? json_encode($workflow_data): null;
        $workflow_record->next_user_responsible = $data['user_assigned_id'] ?? null;
        $workflow_record->associate_type = get_class();
        $workflow_record->associate_id = $this->id;
        $workflow_record->save();


    }

}
