<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class TaskAttributeValue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'task_id',
        'attribute_id',
        'value',
        'num_value',
        'string_value',
        'bool_value',
    ];

    protected $appends = ['value'];

    // protected $casts = [
    //     'value' => AttributeValueCast::class,
    // ];

    public function getValueAttribute()
    {
        $type = $this->attribute->type->value;
        switch ($type) {
            case 'num':
                return $this->attributes['num_value'];
                break;
            case 'string':
                return $this->attributes['string_value'];
                break;
            case 'bool':
                return $this->attributes['bool_value'];
                break;
            default:
                return $this->attributes['string_value'];
                break;
        }
    }

    public function setValueAttribute($value): void
    {
        $type = $this->attribute->type->value;
        switch ($type) {
            case 'num':
                $this->attributes['num_value'] = doubleval($value);
                break;
            case 'string':
                $this->attributes['string_value'] = strval($value);
                break;
            case 'bool':
                $this->attributes['bool_value'] = boolval($value);
                break;
            default:
                $this->attributes['string_value'] = strval($value);
                break;
        }
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
