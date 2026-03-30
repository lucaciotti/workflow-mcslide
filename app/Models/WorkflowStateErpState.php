<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkflowStateErpState extends Model
{
    protected $guarded = [
        'id'
    ];

    public function erpState(): BelongsTo
    {
        return $this->belongsTo(ErpState::class);
    }

    public function workflowState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class);
    }
}
