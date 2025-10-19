<?php

namespace App\Filament\Resources\TaskImportFiles\Pages;

use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Jobs\ProcessTempTasks;
use App\Models\TaskImportFile;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewTaskImportFile extends ViewRecord
{
    protected static string $resource = TaskImportFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('Elabora')
                ->icon(Heroicon::ArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Elabora nuovamente import')
                ->modalDescription('Si desidera ri-processare l\'importazione?')
                ->modalSubmitActionLabel('Si, procedi')
                ->action(fn(TaskImportFile $record) => ProcessTempTasks::dispatch($record->id, $record->hasWarnings)->onQueue('tasks'))
                ->visible(fn(TaskImportFile $record) => in_array($record->status, ['Errore', 'Processato', 'Verificare'])),
            Action::make('download')
                ->label('Download')
                ->color('success')
                ->icon(Heroicon::ArrowDownTray)
                ->action(
                    function (TaskImportFile $record) {
                        return response()->download(storage_path('app/private/' . $record->path), $record->filename);
                    }),
        ];
    }

}
