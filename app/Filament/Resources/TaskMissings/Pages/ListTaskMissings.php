<?php

namespace App\Filament\Resources\TaskMissings\Pages;

use App\Filament\Resources\TaskMissings\TaskMissingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaskMissings extends ListRecords
{
    protected static string $resource = TaskMissingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
