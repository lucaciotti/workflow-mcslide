<?php

namespace App\Filament\Resources\TaskMissings\Pages;

use App\Filament\Resources\TaskMissings\TaskMissingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskMissing extends CreateRecord
{
    protected static string $resource = TaskMissingResource::class;
}
