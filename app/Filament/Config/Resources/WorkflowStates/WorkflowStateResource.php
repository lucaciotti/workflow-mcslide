<?php

namespace App\Filament\Config\Resources\WorkflowStates;

use App\Filament\Config\Resources\WorkflowStates\Pages\CreateWorkflowState;
use App\Filament\Config\Resources\WorkflowStates\Pages\EditWorkflowState;
use App\Filament\Config\Resources\WorkflowStates\Pages\ListWorkflowStates;
use App\Filament\Config\Resources\WorkflowStates\Pages\ViewWorkflowState;
use App\Filament\Config\Resources\WorkflowStates\Schemas\WorkflowStateForm;
use App\Filament\Config\Resources\WorkflowStates\Schemas\WorkflowStateInfolist;
use App\Filament\Config\Resources\WorkflowStates\Tables\WorkflowStatesTable;
use App\Models\WorkflowState;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkflowStateResource extends Resource
{
    protected static ?string $model = WorkflowState::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleGroup;

    protected static string | UnitEnum | null $navigationGroup = 'Gestione WorkFlow';
    protected static ?string $recordTitleAttribute = 'Stati';
    protected static ?string $modelLabel = 'Stato';
    protected static ?string $pluralModelLabel = 'Stati';

    public static function form(Schema $schema): Schema
    {
        return WorkflowStateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkflowStateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowStatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflowStates::route('/'),
            // 'create' => CreateWorkflowState::route('/create'),
            'view' => ViewWorkflowState::route('/{record}'),
            // 'edit' => EditWorkflowState::route('/{record}/edit'),
        ];
    }
}
