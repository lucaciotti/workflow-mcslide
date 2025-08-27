<?php

namespace App\Models;

use App\Enums\TaskTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class Task extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'type',
        'date',
        'num',
        'customer_id',
        'shipping_address_id',
        'carrier',
        'date_shipping',
        'box_glass',
        'product_range_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => TaskTypes::class,
        ];
    }

    // public function getAttr(string $name, $default = null)
    // {
    //     return optional($this->attributeValues
    //         ->where('attribute.name', $name)
    //         ->first())->value ?? $default;
    // }

    // public function setAttr(string $name, $value): void
    // {
    //     if ($this->hasAttr($name)) {
    //         $attr = $this->attributeValues
    //             ->where('attribute.name', $name)
    //             ->first();
    //         $attr->value = $value;
    //         $attr->save();
    //     } else {
    //         throw new \Exception(sprintf('This task may not have the attribute [%s].', $name));
    //     }
    // }

    // public function hasAttr(string $name): bool
    // {
    //     return $this->attributeValues
    //         ->contains('attribute.name', $name);
    // }

    public function taskRowns(): HasMany
    {
        return $this->hasMany(TaskRow::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(TaskAttributeValue::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function productRange(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }
}
