<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\TempTaskResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTempTask extends EditRecord
{
    protected static string $resource = TempTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
