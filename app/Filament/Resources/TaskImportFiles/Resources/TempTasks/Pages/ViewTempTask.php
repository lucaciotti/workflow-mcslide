<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\TempTaskResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTempTask extends ViewRecord
{
    protected static string $resource = TempTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
