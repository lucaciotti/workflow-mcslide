<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempTaskRow extends Model
{
    protected $guarded = ['id'];
    
    public function tempTask()
    {
        return $this->hasOne(TempTask::class);
    }
    
    public function productRange(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }
}
