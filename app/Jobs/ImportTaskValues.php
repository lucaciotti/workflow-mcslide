<?php

namespace App\Jobs;

use App\Imports\TasksImport;
use App\Imports\TempTasksImport;
use App\Models\Task;
use App\Models\TaskImportFile;
use App\Models\TaskValuesImportFile;
use App\Models\TempTask;
use Excel;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ImportTaskValues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TaskValuesImportFile $importedfile;
    private $hasWarnings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_file_id)
    {
        Log::info('ImportTaskValues Job Created');
        $this->importedfile = TaskValuesImportFile::find($import_file_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('ImportTasks Job Started');
        $this->importedfile->status = 'Processing';
        $this->importedfile->save();
        Excel::import(new TasksImport($this->importedfile->id), storage_path('app/private/' . $this->importedfile->path));
    }

    public function failed(\Throwable $e)
    {
        $this->importedfile->status = 'Errore';
        $this->importedfile->save();
        report($e);

        $recipient = $this->importedfile->audits()->get()->first()->user;
        Notification::make()
            ->title('Errore Importazione Valori')
            ->body($e->getMessage())
            ->sendToDatabase($recipient);
    }
}
