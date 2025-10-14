<?php

namespace App\Filament\Resources\TaskImportFiles\Pages;

use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTaskImportFile extends EditRecord
{
    protected static string $resource = TaskImportFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
