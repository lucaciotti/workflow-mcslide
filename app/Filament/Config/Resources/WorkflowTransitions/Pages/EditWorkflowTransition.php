<?php

namespace App\Filament\Config\Resources\WorkflowTransitions\Pages;

use App\Filament\Config\Resources\WorkflowTransitions\WorkflowTransitionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowTransition extends EditRecord
{
    protected static string $resource = WorkflowTransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
