<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempTask extends Model
{
    protected $guarded = ['id'];

    public function taskImportFile() {
        return $this->belongsTo(TaskImportFile::class);
    }

    public function tempTaskRows() {
        return $this->hasMany(TempTaskRow::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function productRange(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }

    public function workFlowState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class, 'workflow_state_id', 'id');
    }
}
