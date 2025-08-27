<?php

namespace App\Filament\Config\Resources\WorkflowTransitions\Pages;

use App\Filament\Config\Resources\WorkflowTransitions\WorkflowTransitionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkflowTransition extends ViewRecord
{
    protected static string $resource = WorkflowTransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
