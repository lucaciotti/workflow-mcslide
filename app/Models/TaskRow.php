<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class TaskRow extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'task_id',
        'product_range_id',
        'qty'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
