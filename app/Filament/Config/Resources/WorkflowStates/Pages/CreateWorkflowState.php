<?php

namespace App\Filament\Config\Resources\WorkflowStates\Pages;

use App\Filament\Config\Resources\WorkflowStates\WorkflowStateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowState extends CreateRecord
{
    protected static string $resource = WorkflowStateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
