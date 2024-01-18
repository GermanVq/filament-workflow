<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];


    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function workflowModels()
    {
        return $this->hasMany(WorkflowModel::class);
    }

}
