<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkflowGate extends Model
{
    protected $guarded = [
        'id'
    ];


    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
    
    public function workflowState(): HasOne
    {
        return $this->hasOne(WorkflowState::class);
    }
}
