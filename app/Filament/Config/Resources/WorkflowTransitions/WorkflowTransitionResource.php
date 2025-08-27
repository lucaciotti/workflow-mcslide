<?php

namespace App\Filament\Config\Resources\WorkflowTransitions;

use App\Filament\Config\Resources\WorkflowTransitions\Pages\CreateWorkflowTransition;
use App\Filament\Config\Resources\WorkflowTransitions\Pages\EditWorkflowTransition;
use App\Filament\Config\Resources\WorkflowTransitions\Pages\ListWorkflowTransitions;
use App\Filament\Config\Resources\WorkflowTransitions\Pages\ViewWorkflowTransition;
use App\Filament\Config\Resources\WorkflowTransitions\Schemas\WorkflowTransitionForm;
use App\Filament\Config\Resources\WorkflowTransitions\Schemas\WorkflowTransitionInfolist;
use App\Filament\Config\Resources\WorkflowTransitions\Tables\WorkflowTransitionsTable;
use App\Models\WorkflowTransition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkflowTransitionResource extends Resource
{
    protected static ?string $model = WorkflowTransition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowRightStartOnRectangle;

    protected static string | UnitEnum | null $navigationGroup = 'Gestione WorkFlow';
    protected static ?string $recordTitleAttribute = 'Transizione Stati';
    protected static ?string $modelLabel = 'Transizione Stato';
    protected static ?string $pluralModelLabel = 'Transizione Stati';

    public static function form(Schema $schema): Schema
    {
        return WorkflowTransitionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkflowTransitionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowTransitionsTable::configure($table);
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
            'index' => ListWorkflowTransitions::route('/'),
            // 'create' => CreateWorkflowTransition::route('/create'),
            'view' => ViewWorkflowTransition::route('/{record}'),
            // 'edit' => EditWorkflowTransition::route('/{record}/edit'),
        ];
    }
}
