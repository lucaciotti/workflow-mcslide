<?php

namespace App\Filament\Config\Resources\Attributes\Pages;

use App\Filament\Config\Resources\Attributes\AttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}
