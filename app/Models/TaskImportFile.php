<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaskImportFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];
    // protected $appends = ['user_create'];

    // protected $casts = [
    //     'value' => AttributeValueCast::class,
    // ];

    public function getUserCreateNameAttribute()
    {
        return $this->audits()->first()->user->name ?? null;
    }

    public function getUserCreateIdAttribute()
    {
        return $this->audits()->first()->user->id ?? null;
    }

    public function getUserLastNameAttribute()
    {
        return $this->audits()->get()->last()->user->name ?? null;
    }

    public function getUserLastIdAttribute()
    {
        return $this->audits()->get()->last()->user->id ?? null;
    }

    public function tempTasks() {
        return $this->hasMany(TempTask::class);
    }
}
