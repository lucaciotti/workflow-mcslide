<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaskImportFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];

    public function tempTasks() {
        return $this->hasMany(TempTask::class);
    }

    public function userCreated()
    {
        return $this->audits()->first()->user;
    }
}
