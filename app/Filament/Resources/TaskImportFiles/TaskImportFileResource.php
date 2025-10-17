<?php

namespace App\Filament\Resources\TaskImportFiles;

use App\Filament\Resources\TaskImportFiles\Pages\CreateTaskImportFile;
use App\Filament\Resources\TaskImportFiles\Pages\EditTaskImportFile;
use App\Filament\Resources\TaskImportFiles\Pages\ListTaskImportFiles;
use App\Filament\Resources\TaskImportFiles\Pages\ManageTaskImportFileTempTask;
use App\Filament\Resources\TaskImportFiles\Pages\ViewTaskImportFile;
use App\Filament\Resources\TaskImportFiles\RelationManagers\TempTasksRelationManager;
use App\Filament\Resources\TaskImportFiles\Schemas\TaskImportFileForm;
use App\Filament\Resources\TaskImportFiles\Schemas\TaskImportFileInfolist;
use App\Filament\Resources\TaskImportFiles\Tables\TaskImportFilesTable;
use App\Models\TaskImportFile;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class TaskImportFileResource extends Resource
{
    protected static ?string $model = TaskImportFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return TaskImportFileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TaskImportFileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaskImportFilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            // TempTasksRelationManager::make(),
            AuditsRelationManager::make(),
            
        ];
    }

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewTaskImportFile::class,
            ManageTaskImportFileTempTask::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaskImportFiles::route('/'),
            'create' => CreateTaskImportFile::route('/create'),
            'view' => ViewTaskImportFile::route('/{record}'),
            // 'edit' => EditTaskImportFile::route('/{record}/edit'),
            'rows' => ManageTaskImportFileTempTask::route('/{record}/rows'),
        ];
    }
}
