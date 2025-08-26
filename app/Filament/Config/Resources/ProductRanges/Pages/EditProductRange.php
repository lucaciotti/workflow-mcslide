<?php

namespace App\Filament\Config\Resources\ProductRanges\Pages;

use App\Filament\Config\Resources\ProductRanges\ProductRangeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductRange extends EditRecord
{
    protected static string $resource = ProductRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
