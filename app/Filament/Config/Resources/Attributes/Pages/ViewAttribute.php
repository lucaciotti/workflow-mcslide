<?php

namespace App\Filament\Config\Resources\Attributes\Pages;

use App\Filament\Config\Resources\Attributes\AttributeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttribute extends ViewRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
