<?php

namespace App\Filament\Config\Resources\ErpStates\Pages;

use App\Filament\Config\Resources\ErpStates\ErpStateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpStates extends ListRecords
{
    protected static string $resource = ErpStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
