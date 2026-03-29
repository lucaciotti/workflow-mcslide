<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskWorkflowStory extends Model
{
    protected $guarded = ['id'];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
    
    public function workflowState(): HasOne
    {
        return $this->hasOne(WorkflowState::class);
    }
}
