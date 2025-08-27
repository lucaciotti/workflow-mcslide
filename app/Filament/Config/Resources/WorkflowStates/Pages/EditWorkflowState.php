<?php

namespace App\Filament\Config\Resources\WorkflowStates\Pages;

use App\Filament\Config\Resources\WorkflowStates\WorkflowStateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowState extends EditRecord
{
    protected static string $resource = WorkflowStateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
