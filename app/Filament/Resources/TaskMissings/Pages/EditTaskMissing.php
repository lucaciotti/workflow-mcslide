<?php

namespace App\Filament\Resources\TaskMissings\Pages;

use App\Filament\Resources\TaskMissings\TaskMissingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaskMissing extends EditRecord
{
    protected static string $resource = TaskMissingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
