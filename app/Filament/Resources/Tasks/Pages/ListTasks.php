<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Imports\TaskImporter;
use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('Importa')->label('Importa'),
            // ImportAction::make()->importer(TaskImporter::class)
        ];
    }
}
