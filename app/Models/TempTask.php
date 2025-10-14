<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTask extends Model
{
    public function taskImportFile() {
        return $this->hasOne(TaskImportFile::class);
    }

    public function tempTaskRows() {
        return $this->hasMany(TempTaskRow::class);
    }
}
