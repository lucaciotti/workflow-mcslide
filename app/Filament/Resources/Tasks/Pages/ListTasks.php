<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Imports\TaskImporter;
use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Filament\Resources\Tasks\TaskResource;
use App\Helpers\Workflow;
use App\Models\Task;
use App\Models\WorkflowState;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class ListTasks extends ListRecords implements HasFilamentTablePresets
{
    use HasResizableColumn;
    use WithFilamentTablePresets;

    protected static string $resource = TaskResource::class;

    public function mount(): void
    {
        parent::mount();

        $this->applyDefaultPreset();
    }

    protected function getTableHeaderActions(): array
    {
        return $this->retrieveVisiblePresetActions();
    }

    protected function handleTableFilterUpdates(): void
    {
        $this->selectedFilamentPreset = null;

        parent::handleTableFilterUpdates();
    }

    public function updatedTableSort(): void
    {
        $this->selectedFilamentPreset = null;

        parent::updatedTableSort();
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make('Importa')->label('Importa'),
            Action::make('Importa')->label('Importa')->url(TaskImportFileResource::getUrl()),
            ManageTablePresetAction::make(),
            // ImportAction::make()->importer(TaskImporter::class)
        ];
    }

    public function getTabs(): array
    {
        $workflowStates = (new Workflow)->getAllStates();
        $statesNotWorkflow = WorkflowState::whereNotIn('id', $workflowStates->pluck('id'))->get();
        $tabs = ['Tutti' => Tab::make()];
        foreach ($workflowStates as $state) {
            $count = Task::query()->where('workflow_state_id', $state->id)->count();
            $tabs[$state->name] = Tab::make()
                ->badge($count)
                ->visible($count>0)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('workflow_state_id', $state->id));
        }
        foreach ($statesNotWorkflow as $state) {
            $count = Task::query()->where('workflow_state_id', $state->id)->count();
            $tabs[$state->name] = Tab::make()
                ->badge($count)
                ->badgeColor('danger')
                ->visible($count>0)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('workflow_state_id', $state->id));
        }
        return $tabs;
    }
}
