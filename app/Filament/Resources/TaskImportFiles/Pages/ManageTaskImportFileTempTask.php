<?php

namespace App\Filament\Resources\TaskImportFiles\Pages;

use App\Filament\Resources\TaskImportFiles\Resources\TempTasks\TempTaskResource;
use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;

class ManageTaskImportFileTempTask extends ManageRelatedRecords
{
    protected static string $resource = TaskImportFileResource::class;

    protected static string $relationship = 'tempTasks';

    protected static ?string $relatedResource = TempTaskResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
