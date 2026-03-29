<?php

namespace App\Models;

use App\Enums\AttributeTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Attribute extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [
        'id'
    ];
    // protected $fillable = [
    //     'department_id',
    //     'name',
    //     'type',
    // ];

    protected function casts(): array
    {
        return [
            'type' => AttributeTypes::class,
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(TaskAttributeValue::class);
    }
}
