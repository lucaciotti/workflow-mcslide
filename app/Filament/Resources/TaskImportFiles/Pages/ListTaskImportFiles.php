<?php

namespace App\Filament\Resources\TaskImportFiles\Pages;

use App\Filament\Resources\TaskImportFiles\TaskImportFileResource;
use App\Models\TaskImportFile;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListTaskImportFiles extends ListRecords
{
    protected static string $resource = TaskImportFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('uploadFile')
                ->schema([
                    FileUpload::make('filename')
                        ->label('Carica tracciato excel')
                        ->openable()
                        // ->directory('task_import_files')
                        ->visibility('public')
                        ->storeFiles(false)
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ])
                ->action(function (array $data): void {
                    if (array_key_exists('filename', $data)){
                        $date = Carbon::now();
                        $file = $data['filename'];
                        $extension = $file->getClientOriginalExtension();
                        $newName = $date->format('Ymd') . '_' . $date->format('Hmi');
                        $path = $file->storeAs('task_import_files', $newName . '.' . $extension);
                        $extradata = [
                            'status' => 'File Caricato',
                            'path' => $path,
                        ];
                        $planImportFile = TaskImportFile::create(array_merge($data, $extradata));
                        // $record->author()->associate($data['authorId']);
                        // $record->save();
                    } else {
                        Notification::make()
                            ->title('Nessun file caricato!')
                            ->warning()
                            ->send();
                    }
                })
        ];
    }
}
