<?php

namespace App\Filament\Config\Resources\WorkflowStates\Pages;

use App\Filament\Config\Resources\WorkflowStates\WorkflowStateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkflowState extends ViewRecord
{
    protected static string $resource = WorkflowStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
