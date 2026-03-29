<?php

namespace App\Filament\Config\Resources\Components\Pages;

use App\Filament\Config\Resources\Components\ComponentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComponent extends CreateRecord
{
    protected static string $resource = ComponentResource::class;
}
