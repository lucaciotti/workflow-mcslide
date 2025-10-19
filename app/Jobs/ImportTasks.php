<?php

namespace App\Jobs;

use App\Imports\TempTasksImport;
use App\Models\Task;
use App\Models\TaskImportFile;
use App\Models\TempTask;
use Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ImportTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TaskImportFile $importedfile;
    private $hasWarnings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_file_id)
    {
        Log::info('ImportTasks Job Created');
        $this->importedfile = TaskImportFile::find($import_file_id);
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
        TempTask::where('task_import_file_id', $this->importedfile->id)->delete();
        Excel::import(new TempTasksImport($this->importedfile->id), storage_path('app/private/' . $this->importedfile->path));
        ProcessTempTasks::dispatch($this->importedfile->id, $this->hasWarnings)->onQueue('tasks');
    }

    public function failed(\Throwable $e)
    {
        $this->importedfile->status = 'Errore';
        $this->importedfile->save();
        report($e);

        $recipient = $this->importedfile->audits()->get()->last()->user;
        Notification::make()
            ->title('Errore Importazione Ordini')
            ->error()
            ->body($e->getMessage())
            ->sendToDatabase($recipient);
    }
}
