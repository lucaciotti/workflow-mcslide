<?php

namespace App\Filament\Resources\TaskImportFiles\Tables;

use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Jobs\ProcessTempTasks;
use App\Models\TaskImportFile;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class TaskImportFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filename')
                    ->searchable(),
                TextColumn::make('status')->label('Stato')
                    ->searchable(),
                TextColumn::make('date_upload')->label('Data upload')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_last_import')->label('Data processato')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Righe file')
                    ->icon(Heroicon::Bars4)
                    ->url(fn(TaskImportFile $record): string => TaskImportFileResource::getUrl('rows', [
                        'record' => $record->id,
                    ])),
                // ViewAction::make(),
                Action::make('Elabora')
                    ->icon(Heroicon::ArrowPath)
                    ->requiresConfirmation()
                    ->modalHeading('Elabora nuovamente import')
                    ->modalDescription('Si desidera ri-processare l\'importazione?')
                    ->modalSubmitActionLabel('Si, procedi')
                    ->action(fn(TaskImportFile $record) => ProcessTempTasks::dispatch($record->id, $record->hasWarnings)->onQueue('tasks'))
                    ->visible(fn(TaskImportFile $record) => in_array($record->status, ['Errore', 'Processato', 'Processing']))
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date_upload', direction: 'desc')
            ->poll('5s');
    }
}
