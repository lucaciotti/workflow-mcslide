<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTaskRow extends Model
{
    public function tempTask()
    {
        return $this->hasOne(TempTask::class);
    }
}
