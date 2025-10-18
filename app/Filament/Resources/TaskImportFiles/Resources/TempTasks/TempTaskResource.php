<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\CreateTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\EditTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\ListTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\ViewTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\RelationManagers\TempTaskRowsRelationManager;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas\TempTaskForm;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas\TempTaskInfolist;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Tables\TempTasksTable;
use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Models\TempTask;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TempTaskResource extends Resource
{
    protected static ?string $model = TempTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TaskImportFileResource::class;

    protected static ?string $modelLabel = 'Riga file import';
    protected static ?string $pluralModelLabel = 'Righe file import';

    public static function form(Schema $schema): Schema
    {
        return TempTaskForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TempTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TempTasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TempTaskRowsRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            // 'create' => CreateTempTask::route('/create'),
            'view' => ViewTempTask::route('/{record}'),
            // 'edit' => EditTempTask::route('/{record}/edit'),
        ];
    }
}
