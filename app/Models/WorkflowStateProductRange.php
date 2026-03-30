<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStateProductRange extends Model
{
    protected $guarded = [
        'id'
    ];

    public function productRange(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }

    public function workflowState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class);
    }
}
