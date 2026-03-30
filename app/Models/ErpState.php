<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ErpState extends Model
{
    protected $guarded = [
        'id'
    ];

    // public function workflowStates(): BelongsToMany
    // {
    //     return $this->belongsToMany(WorkflowState::class, WorkflowStateErpState::class, 'erp_state_id',  'workflow_state_id');
    // }

    public function workflowStates(): HasMany
    {
        return $this->hasMany(WorkflowStateErpState::class);
    }
}
