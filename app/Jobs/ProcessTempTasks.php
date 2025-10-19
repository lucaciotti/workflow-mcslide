<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\TaskImportFile;
use App\Models\TaskRow;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ProcessTempTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TaskImportFile $importedfile;
    private $hasWarnings;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_file_id, $hasWarnings = false)
    {
        Log::info('ProcessTempTasks Job Created');
        $this->importedfile = TaskImportFile::with('tempTasks')->find($import_file_id);
        $this->hasWarnings = $hasWarnings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('ProcessTempTasks Job Started');
        $this->analizeTempTasks();
        try {
            DB::transaction(function () {
                $tempTasks = $this->importedfile->tempTasks;
                foreach ($tempTasks as $tempTask) {
                    $dataTask = [];
                    $dataTaskRow = [];
                    $task = null;
                    if ($tempTask->selected) {
                        $dataTask = $tempTask->toArray();
                        if (empty($tempTask->task_id)) {
                            $task = Task::create($dataTask);
                        } else {
                            $task = Task::find($tempTask->task_id);
                            $task->update($dataTask);
                        }
                        $tempTask->imported = true;
                        $tempTask->date_last_import = Carbon::now();
                        $tempTask->save();
                        $n_row = 1;
                        foreach ($tempTask->tempTaskRows as $tempTaskRow) {
                            $dataTaskRow = $tempTaskRow->toArray();
                            if (TaskRow::where('task_id', $task->id)->exists()){
                                TaskRow::where('task_id', $task->id)->delete();
                            }
                            $taskrow = TaskRow::create($dataTaskRow);
    
                            if($n_row==1){
                                $task->product_range_id = $taskrow->product_range_id;
                                $task->box_glass = $taskrow->box_glass;
                                $task->save();
                            }
                            if($taskrow->productRange->primary){
                                $task->product_range_id = $taskrow->product_range_id;
                                $task->box_glass = $taskrow->box_glass;
                                $task->save();
                            }
    
                            $tempTaskRow->imported = true;
                            $tempTaskRow->save();
                            $n_row++;
                        }
                    }
                }
                $this->importedfile->date_last_import = Carbon::now();
                $this->importedfile->status = ($this->hasWarnings) ? 'Verificare' : 'Processato';
                $this->importedfile->save();
                
                // La Notifica di import Nuove Pianificazioni è da inviare a tutti
                $notifyUsers = User::all();
                Notification::make()
                    ->title('Nuovi Ordini importati!')
                    ->body('File [' . $this->importedfile->name . '] processato correttamente!')
                    ->sendToDatabase($notifyUsers);
            });
            Log::info('ProcessTempTasks Job Ended');
        } catch (\Throwable $th) {
            report($th);
            $recipient = $this->importedfile->audits()->get()->last()->user;
            Notification::make()
                ->title('Errore Importazione Ordini')
                ->body($th->getMessage())
                ->sendToDatabase($recipient);
            return false;
        }
    }

    private function analizeTempTasks()
    {
        DB::transaction(function () {
            $temptasks = $this->importedfile->tempTasks;
            $this->hasWarnings = false;
            foreach ($temptasks as $temptask) {
                $task_wheredata = [
                    ['num', $temptask->num],
                    ['customer_id', $temptask->customer_id],
                    ['date', $temptask->date],
                    ['type', $temptask->type],
                ];
                $task = Task::where($task_wheredata)->first();
                if ($task != null) {
                    $temptask->task_id = $task->id;
                    try {
                        if ($task->audits->last()->user_id != null) {
                            $temptask->warning = true;
                            $temptask->error = 'Commessa già importata e modificata da utente!';
                            $this->hasWarnings = true;
                        }
                    } catch (\Throwable $th) {//throw $th;
                    }
                }
                if ($temptask->tempTaskRows->count() > 1) {
                    $temptask->warning = true;
                    $temptask->error = 'Presenti molteplici gamme prodotto!';
                    $this->hasWarnings = true;
                }
                $temptask->selected = true;
                $temptask->save();
            }
            if ($this->hasWarnings) {
                $recipient = $this->importedfile->audits()->get()->last()->user;
                Notification::make()
                    ->warning()
                    ->title('Importazione Ordini da verificare!')
                    ->body('Alcune righe hanno dei Warnings! [' . $this->importedfile->filename . ']')
                    ->sendToDatabase($recipient);
            }
            Log::info('ProcessTempTasks Analizied TempTasks');
        });
    }

    public function failed(\Throwable $th)
    {
        $this->importedfile->status = 'Errore';
        $this->importedfile->save();
        report($th);
        $recipient = $this->importedfile->audits()->get()->last()->user;
        Notification::make()
            ->title('Errore Importazione Ordini')
            ->body($th->getMessage())
            ->sendToDatabase($recipient);
        return false;
    }
}
