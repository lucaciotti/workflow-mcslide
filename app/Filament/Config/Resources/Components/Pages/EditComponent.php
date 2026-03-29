<?php

namespace App\Filament\Config\Resources\Components\Pages;

use App\Filament\Config\Resources\Components\ComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComponent extends EditRecord
{
    protected static string $resource = ComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
