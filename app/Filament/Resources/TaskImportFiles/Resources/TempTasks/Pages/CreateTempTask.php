<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\TempTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTempTask extends CreateRecord
{
    protected static string $resource = TempTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->createAnother(false),
        ];
    }
}
