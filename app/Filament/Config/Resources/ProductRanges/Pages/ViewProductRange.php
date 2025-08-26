<?php

namespace App\Filament\Config\Resources\ProductRanges\Pages;

use App\Filament\Config\Resources\ProductRanges\ProductRangeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductRange extends ViewRecord
{
    protected static string $resource = ProductRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
