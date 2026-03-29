<?php

namespace App\Filament\Config\Resources\Products\Pages;

use App\Filament\Config\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
