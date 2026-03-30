<?php

namespace App\Filament\Resources\TaskMissings;

use App\Filament\Resources\TaskMissings\Pages\CreateTaskMissing;
use App\Filament\Resources\TaskMissings\Pages\EditTaskMissing;
use App\Filament\Resources\TaskMissings\Pages\ListTaskMissings;
use App\Filament\Resources\TaskMissings\Schemas\TaskMissingForm;
use App\Filament\Resources\TaskMissings\Tables\TaskMissingsTable;
use App\Models\TaskMissing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaskMissingResource extends Resource
{
    protected static ?string $model = TaskMissing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    // protected static ?string $recordTitleAttribute = 'num';
    protected static ?string $modelLabel = 'Codice Mancante';
    protected static ?string $pluralModelLabel = 'Codici Mancanti';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TaskMissingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaskMissingsTable::configure($table);
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
            'index' => ListTaskMissings::route('/'),
            // 'create' => CreateTaskMissing::route('/create'),
            // 'edit' => EditTaskMissing::route('/{record}/edit'),
        ];
    }
}
