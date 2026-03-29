<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role;

class WorkflowState extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // protected $fillable = [
    //     'name',
    //     'enable_gate',
    //     'gate_day'
    // ];
    protected $guarded = [
        'id'
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, WorkflowStateRolePermission::class, 'state_id', 'role_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function gates(): HasMany
    {
        return $this->hasMany(WorkflowGate::class, 'id', 'workflow_state_id');
    }
    
    public function productRanges(): BelongsToMany
    {
        return $this->belongsToMany(ProductRange::class, WorkflowStateProductRange::class, 'workflow_state_id', 'product_range_id');
        }
        
        // public function erpStates(): BelongsToMany
        // {
        //     return $this->belongsToMany(ErpState::class, WorkflowStateErpState::class, 'workflow_state_id', 'erp_state_id');
        // }
        public function erpStates(): HasMany
        {
            return $this->hasMany(WorkflowStateErpState::class, 'id', 'workflow_state_id');
        }
}
