<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Actions\ViewWorkflow;
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
use Filament\Support\Icons\Heroicon;
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
        $actions = $this->retrieveVisiblePresetActions();
        // array_push(
        //     $actions, 
        //     Action::make('panel-fullscreen')->hiddenLabel()->icon(Heroicon::ArrowsPointingOut)->color('default')->extraAttributes(
        //         [
        //             'id' => 'panel-fullscreen',
        //         ]
        //     )
        // );
        return $actions;
    }

    protected function handleTableFilterUpdates(): void
    {
        // $this->selectedFilamentPreset = null;

        parent::handleTableFilterUpdates();
    }

    public function updatedTableSort(): void
    {
        // $this->selectedFilamentPreset = null;

        parent::updatedTableSort();
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make('Importa')->label('Importa'),
            ViewWorkflow::make(),
            // Action::make('Importa')->label('Importa')->url(TaskImportFileResource::getUrl()),
            // ManageTablePresetAction::make()->label('')->outlined(),
            // ImportAction::make()->importer(TaskImporter::class)
        ];
    }

    public function getTabs(): array
    {
        $workflowStates = (new Workflow)->getAllStates();
        $statesNotWorkflow = WorkflowState::whereNotIn('id', $workflowStates->pluck('id'))->get();
        // $tabs = ['Tutti' => Tab::make()];
        $departments = [];
        $existWorflowStatesWithoutDepartment = false;
        foreach ($workflowStates as $state) {
            if($state->department){
                if (!in_array($state->department, $departments, true)) {
                    array_push($departments, $state->department);
                }
            } else {
                $existWorflowStatesWithoutDepartment = true;
            }
        }
        foreach ($departments as $stateDepartment) {
            $count = Task::query()->whereHas('workFlowState', function ($q) use ($stateDepartment) {
                $q->where('department_id', $stateDepartment->id);
            })->count();
            $tabs[$stateDepartment->name] = Tab::make()
                ->badge($count)
                ->visible($count>0)
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('workFlowState', function ($q) use ($stateDepartment) {
                $q->where('department_id', $stateDepartment->id);
            }));
        }
        if($existWorflowStatesWithoutDepartment){
            $count = Task::query()->whereNotIn('workflow_state_id', $statesNotWorkflow->pluck('id'))->whereHas('workFlowState', function ($q) {
                $q->where('department_id', null);
            })->count();
            $tabs['Senza Reparto!'] = Tab::make()
                ->badge($count)
                ->badgeColor('warning')
                ->visible($count > 0)
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNotIn('workflow_state_id', $statesNotWorkflow->pluck('id'))->whereHas('workFlowState', function ($q) {
                    $q->where('department_id', null);
                }));
        }
        // foreach ($statesNotWorkflow as $state) {
        //     $count = Task::query()->where('workflow_state_id', $state->id)->count();
        //     $tabs[$state->name] = Tab::make()
        //         ->badge($count)
        //         ->badgeColor('danger')
        //         ->visible($count>0)
        //         ->modifyQueryUsing(fn(Builder $query) => $query->where('workflow_state_id', $state->id));
        // }
        $count = Task::query()->where('workflow_state_id', null)->count();
        // $count = Task::query()->whereIn('workflow_state_id', $statesNotWorkflow->pluck('id'))->count();
        $tabs['WORKFLOW ASSENTE!'] = Tab::make()
            ->badge($count)
            ->badgeColor('danger')
            ->visible($count>0)
            ->modifyQueryUsing(fn(Builder $query) => $query->where('workflow_state_id', null));
            // ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('workflow_state_id', $statesNotWorkflow->pluck('id')));

        $count = Task::query()->where('suspended', true)->count();
        $tabs['SOSPESI!'] = Tab::make()
            ->badge($count)
            ->badgeColor('warning')
            ->visible($count>0)
            ->modifyQueryUsing(fn(Builder $query) => $query->where('suspended', true));
        $count = Task::query()->where('deleted', true)->count();
        $tabs['CANCELLATI!'] = Tab::make()
            ->badge($count)
            ->badgeColor('danger')
            ->visible($count>0)
            ->modifyQueryUsing(fn(Builder $query) => $query->where('deleted', true));
        $count = Task::query()->where('ended', true)->count();
        $tabs['TERMINATI!'] = Tab::make()
            ->badge($count)
            ->visible($count>0)
            ->modifyQueryUsing(fn(Builder $query) => $query->where('ended', true));

        return $tabs;
    }
}
