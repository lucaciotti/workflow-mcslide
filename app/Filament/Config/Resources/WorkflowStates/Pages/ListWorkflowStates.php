<?php

namespace App\Filament\Config\Resources\WorkflowStates\Pages;

use App\Filament\Config\Resources\WorkflowStates\WorkflowStateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflowStates extends ListRecords
{
    protected static string $resource = WorkflowStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
