<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class WorkflowStateCategory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
    ];

    // public function tasks(): HasMany
    // {
    //     return $this->hasMany(Product::class);
    // }

    public function workFlowState(): HasMany
    {
        return $this->hasMany(WorkflowState::class);
    }
}
