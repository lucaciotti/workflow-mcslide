<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role;

class WorkflowState extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'is_gate',
        'gate_day'
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, WorkflowStateRolePermission::class, 'state_id', 'role_id');
    }

    public function workFlowStateCategory(): BelongsTo
    {
        return $this->belongsTo(WorkflowStateCategory::class, 'workflow_state_category_id', 'id');
    }
}
