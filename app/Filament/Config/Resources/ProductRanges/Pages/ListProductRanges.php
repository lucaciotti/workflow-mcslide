<?php

namespace App\Filament\Config\Resources\ProductRanges\Pages;

use App\Filament\Config\Resources\ProductRanges\ProductRangeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductRanges extends ListRecords
{
    protected static string $resource = ProductRangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
