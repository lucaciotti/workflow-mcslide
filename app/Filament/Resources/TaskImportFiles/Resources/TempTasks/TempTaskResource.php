<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\CreateTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\EditTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Pages\ViewTempTask;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas\TempTaskForm;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas\TempTaskInfolist;
use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Tables\TempTasksTable;
use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Models\TempTask;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TempTaskResource extends Resource
{
    protected static ?string $model = TempTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TaskImportFileResource::class;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTempTask::route('/create'),
            'view' => ViewTempTask::route('/{record}'),
            'edit' => EditTempTask::route('/{record}/edit'),
        ];
    }
}
