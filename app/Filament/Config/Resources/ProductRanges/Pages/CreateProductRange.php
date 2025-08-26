<?php

namespace App\Filament\Config\Resources\ProductRanges\Pages;

use App\Filament\Config\Resources\ProductRanges\ProductRangeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductRange extends CreateRecord
{
    protected static string $resource = ProductRangeResource::class;
}
