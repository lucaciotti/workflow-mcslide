<?php

namespace App\Filament\Config\Resources\WorkflowTransitions\Pages;

use App\Filament\Config\Resources\WorkflowTransitions\WorkflowTransitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowTransition extends CreateRecord
{
    protected static string $resource = WorkflowTransitionResource::class;
}
