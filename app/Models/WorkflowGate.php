<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkflowGate extends Model
{
    protected $guarded = [
        'id'
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    public function workflowState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class);
    }
}
