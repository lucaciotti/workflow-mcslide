<?php

namespace App\Filament\Config\Resources\WorkflowTransitions\Pages;

use App\Filament\Config\Resources\WorkflowTransitions\WorkflowTransitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflowTransitions extends ListRecords
{
    protected static string $resource = WorkflowTransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
