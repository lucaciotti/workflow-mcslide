<?php

namespace App\Filament\Config\Resources\ErpStates\Pages;

use App\Filament\Config\Resources\ErpStates\ErpStateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpState extends EditRecord
{
    protected static string $resource = ErpStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
