<?php

namespace App\Filament\Resources\TaskImportFiles\Pages;

use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTaskImportFile extends ViewRecord
{
    protected static string $resource = TaskImportFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
