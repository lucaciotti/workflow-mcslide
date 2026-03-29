<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $guarded = [
        'id'
    ];


    public function productRange(): HasOne
    {
        return $this->hasOne(ProductRange::class);
    }
    
    public function gates(): HasMany
    {
        return $this->hasMany(WorkflowGate::class, 'id', 'product_id');
    }
}
