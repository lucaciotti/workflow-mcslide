<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Enums\TaskTypes;
use App\Exports\TasksExport;
use App\Helpers\Workflow;
use App\Jobs\ImportTaskValues;
use App\Models\Attribute;
use App\Models\Department;
use App\Models\Task;
use App\Models\TaskValuesImportFile;
use App\Models\TaskWorkflowStory;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Kirschbaum\Commentions\Filament\Actions\CommentsAction;
use Maatwebsite\Excel\Facades\Excel;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        $attrRepeaters = [];
        foreach (Department::all() as $cat) {
            $singleAttrRepeaters = [];
            foreach (Attribute::where('department_id', $cat->id)->get() as $attr) {
                if ($attr->type=='date'){
                        array_push(
                            $singleAttrRepeaters,
                            TextColumn::make('attribute_'.$attr->id)->type('datetime-local')
                                ->getStateUsing(fn($record) => $record->attributeValues()->where('attribute_id', $attr->id)->first()->value->toDateTimeLocalString() ?? '-')
                                ->label($attr->name)
                                // ->beforeStateUpdated(function ($record, $state) use ($attr) {
                                //     $record->attributeValues()->updateOrCreate(['attribute_id' => $attr->id, 'value' => $state]);
                                // })
                                // ->updateStateUsing(function ($record, $state) { return $state; })
                                ->sortable()
                                ->toggleable(),
                            // TextInputColumn::make('attribute_'.$attr->id)->type('datetime-local')
                            //     ->getStateUsing(fn($record) =>
                            //     $record->attributeValues()
                            //         ->where('attribute_id', $attr->id)->first()->value->toDateTimeLocalString() ?? '-')
                            //     ->label($attr->name)
                            //     ->beforeStateUpdated(function ($record, $state) use ($attr) {
                            //         $record->attributeValues()->updateOrCreate(['attribute_id' => $attr->id, 'value' => $state]);
                            //     })
                            //     ->updateStateUsing(function ($record, $state) { return $state; })
                            //     ->sortable()
                            //     ->toggleable(),
                        );                
                    } else {
                        array_push(
                            $singleAttrRepeaters,
                            TextColumn::make('attribute_'.$attr->id)
                                ->getStateUsing(fn($record) => $record->attributeValues()->where('attribute_id', $attr->id)->first()->value ?? '-')
                                ->label($attr->name)
                                // ->beforeStateUpdated(function ($record, $state) use ($attr) {
                                //     $record->attributeValues()->updateOrCreate(['attribute_id' => $attr->id, 'value' => $state]);
                                // })
                                // ->updateStateUsing(function ($record, $state) { return $state; })
                                ->sortable()
                                ->toggleable(),
                            // TextInputColumn::make('attribute_'.$attr->id)
                            //     ->getStateUsing(fn($record) =>
                            //     $record->attributeValues()
                            //         ->where('attribute_id', $attr->id)->first()->value ?? '-')
                            //     ->label($attr->name)
                            //     ->beforeStateUpdated(function ($record, $state) use ($attr) {
                            //         $record->attributeValues()->updateOrCreate(['attribute_id' => $attr->id, 'value' => $state]);
                            //     })
                            //     ->updateStateUsing(function ($record, $state) { return $state; })
                            //     ->sortable()
                            //     ->toggleable(),
                        );                
                    }
            }
            array_push(
                $attrRepeaters,
                ColumnGroup::make($cat->name, [
                    ...$singleAttrRepeaters,
                ])
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),
            );
        }
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipologia')
                    ->badge()
                    ->searchable(),
                TextColumn::make('workFlowState.name')->label('Stato')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('num')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')->label('Data')
                    ->date()
                    ->sortable(),
                TextColumn::make('customer.name')->label('Cliente')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('shippingAddress.name')->label('Ind.Spedizione')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('carrier')->label('Vettore')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('product.code')->label('Prodotto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('productRange.name')->label('Fam.Prodotto')
                    ->placeholder('- ATTENZIONE -')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('date_shipping')->label('Data Spedizione')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('box_glass')->label('Vetro')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('compensatori')->label('Compensatori')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('binari')->label('Lav.su Binari')
                    ->boolean()
                    ->toggleable(),
                ...$attrRepeaters,
                TextColumn::make('created_at')->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Aggiornato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->label('Tipologia')
                    ->options(TaskTypes::class),
                DateRangeFilter::make('date')->label('Data Ordine'),
                // DateRangeFilter::make('updated_at')->label('Data ultima modifica'),
                SelectFilter::make('customer')->label('Clienti')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('productRange_id')->label('Famiglia Prodotto')
                    ->relationship('productRange', 'name')
                    ->searchable(),
            ])
            ->recordActions([
            // ViewAction::make(),
                Action::make('transition')->label('Modifica Stato       ')->hiddenLabel(true)->tooltip('Modifica Stato')->outlined()->icon(Heroicon::ArrowRightStartOnRectangle)->color('warning')
                    ->schema([
                        Select::make('state_id')->label('Avanza Stato')
                            ->searchable()
                            ->options(function (Task $record): array {
                                return (new Workflow)->getNextAvailableState($record);
                            }),
                        Textarea::make('comment')->label('Commento')
                    ])
                    ->action(function (array $data, Task $record) {
                        // GESTIONE STORIA DELLO STATO
                        // Prendo ultimo TaskHistoryAttivo
                        $oldStory = TaskWorkflowStory::where('end', null)->where('task_id', $record->id)->first();
                        if($oldStory){
                            $oldStory->end = now();
                            $oldStory->save();
                        }
                        $newStory = TaskWorkflowStory::create([
                            'task_id' => $record->id,
                            'workflow_state_id' => (int)  $data['state_id'],
                            'comment' => $data['comment'],
                            ]);
                        $record->workflow_state_id = (int) $data['state_id'];
                        $record->save();
                    }),
                ViewAction::make('workflow_story')->label('Storia Stati')->hiddenLabel(true)->tooltip('Storia Stati')->outlined()->icon(Heroicon::BarsArrowDown)->color('success')
                    ->schema([
                    Repeater::make('workflowStories')->label('Storia Stati')->columns(2)->hiddenLabel(true)->relationship()
                        ->table([
                            TableColumn::make('Data inizio')->width('200px'),
                            TableColumn::make('Stato'),
                            TableColumn::make('Commento'),
                            TableColumn::make('Data fine')->width('200px'),
                        ])
                        // ->compact()
                        ->schema([
                            DateRangePicker::make('start'),
                            TextEntry::make('workflowState.name')->hiddenLabel(),
                            Textarea::make('comment')->label('Commento'),
                            DateRangePicker::make('end'),
                        ]),
                ]),
                CommentsAction::make()->hiddenLabel(true)->tooltip('Commenti')
                    ->mentionables(User::all()),
                EditAction::make()->hiddenLabel(true)->tooltip('Modifica'),
                ViewAction::make('missing_view')->label('Visualizza Codici Mancanti')->hiddenLabel(true)->tooltip('Visualizza Codici Mancanti')->outlined()->extraAttributes(['class' >= 'w-full'])->icon(Heroicon::OutlinedExclamationTriangle)->color('success')
                    ->visible(fn(Task $record): bool => $record->has_missing ?? false)
                    ->schema([
                        Repeater::make('missings')->label('Mancanti')->columns(2)->hiddenLabel(true)->relationship()
                        ->table([
                            TableColumn::make('Codice Componente'),
                            TableColumn::make('Qta')->width('200px'),
                        ])
                        // ->compact()
                        ->schema([
                            Select::make('component_id')
                                ->label('Componente')
                                ->relationship('component', 'code')
                                ->searchable()
                                ->preload(),
                            TextInput::make('qty')->label('Qta')
                                ->visible()
                                ->numeric(),
                        ]),
                    ])
            ])->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                ManageTablePresetAction::make()->label('')->outlined(),
                BulkActionGroup::make([
                // BulkAction::make('Genera Report')
                //     ->icon('heroicon-m-arrow-down-tray')
                //     ->openUrlInNewTab()
                //     ->deselectRecordsAfterCompletion()
                //     ->action(function (Collection $records) {
                //         return response()->streamDownload(function () use ($records) {
                //             echo Pdf::loadHTML(
                //                 Blade::render('export.pdf.task', ['records' => $records])
                //             )->setPaper('a4', 'landscape')->stream();
                //         }, 'Ordini_pianificati.pdf');
                //     }),

                BulkAction::make('transition')->label('Modifica Stato')->outlined()->icon(Heroicon::ArrowRightStartOnRectangle)->color('info')
                    // ->accessSelectedRecords()
                    ->schema([
                        Select::make('state_id')->label('Avanza Stato')
                            ->searchable()
                            ->options(function (Collection $records): array {
                                // dd($records);
                                return (new Workflow)->getNextAvailableState();
                            }),
                        Textarea::make('comment')->label('Commento')
                    ])
                    ->action(function (array $data, BulkAction $action, Collection $records) {
                        //  CONTROLLO CHE I RECORD SELEZIONATI ABBIANO LO STESSO STATO DI PARTENZA E CHE LO STATO DI ARRIVO NON CONTENGA FAMIGLIE PRODOTTO
                        $lastRecordStateId = null;
                        $lastProductRangeId = null;
                        $mustSameProductRange = false;
                        $stateTo = WorkflowState::find((int) $data['state_id']);
                        if($stateTo->has_product_range && $stateTo->productRanges->count()>0){
                            $mustSameProductRange = true;
                        }
                        // dd($records);
                        $records->each(function (Model $record) use ($action, $lastRecordStateId, $lastProductRangeId, $mustSameProductRange) {
                            if ($lastRecordStateId==null) {
                                $lastRecordStateId = $record->workflow_state_id;
                            }
                            if ($lastRecordStateId != $record->workflow_state_id) {
                                $action->reportBulkProcessingFailure(
                                'state_not_same',
                                message: 'Una o più righe hanno Stato differente.');
                            }
                            if ($mustSameProductRange){
                                if ($lastProductRangeId==null) {
                                    $lastProductRangeId = $record->product_range_id;
                                }
                                if ($lastProductRangeId != $record->product_range_id) {
                                    $action->reportBulkProcessingFailure(
                                        'productrange_not_same',
                                        message: 'Una o più righe hanno Famiglia Prodotto differente.'
                                    );
                                }
                            }

                        });
                        // GESTIONE STORIA DELLO STATO
                        // Prendo ultimo TaskHistoryAttivo
                        $records->each(function (Model $record) use ($data) {
                            $oldStory = TaskWorkflowStory::where('end', null)->where('task_id', $record->id)->first();
                            if ($oldStory) {
                                $oldStory->end = now();
                                $oldStory->save();
                            }
                            $newStory = TaskWorkflowStory::create([
                                'task_id' => $record->id,
                                'workflow_state_id' => (int)  $data['state_id'],
                                'comment' => $data['comment'],
                            ]);
                            $record->workflow_state_id = (int) $data['state_id'];
                            $record->save();
                        });
                    })->after(fn($livewire) => $livewire->resetTable())
                    ->successNotificationTitle('Modifica Stati Avvenuta')
                    ->failureNotificationTitle('Fallita Modifica Stati'),

                BulkAction::make('Sospendi')->outlined()->icon(Heroicon::OutlinedPauseCircle)->color('warning')
                    ->requiresConfirmation()
                    ->action(function(Collection $records) { 
                        $records->each(function (Model $record) {
                            $record->suspended = true;
                            $record->save();
                        });
                    })->after(fn($livewire) => $livewire->resetTable()),

                BulkAction::make('Riprendi')->outlined()->icon(Heroicon::OutlinedPlayCircle)->color('success')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $records->each(function (Model $record) {
                            $record->suspended = false;
                            $record->save();
                        });
                    })->after(fn($livewire) => $livewire->resetTable()),

                BulkAction::make('Cancella')->outlined()->icon(Heroicon::OutlinedTrash)->color('danger')
                    ->requiresConfirmation()
                    ->action(function(Collection $records) { 
                        $records->each(function (Model $record) {
                            $record->deleted = true;
                            $record->save();
                        });
                        // redirect(static::getUrl('index'));
                    })->after(fn($livewire) => $livewire->resetTable()),


                // DeleteBulkAction::make(),
                ]),
                Action::make('exportExcel')
                    ->label('Esporta Colonne')
                    ->icon(Heroicon::ArrowDownTray)
                    ->action(function (Table $table) {
                        // $table->getVisibleColumns()
                        $date = Carbon::now();
                        $exportName = 'CO_' . $date->format('Ymd') . '_' . $date->format('Hmi') . '.xlsx';
                        return Excel::download(new TasksExport($table->getQuery(), $table->getVisibleColumns()), $exportName);
                    }),
                // Action::make('importExcel')
                //     ->label('Importa Valori')
                //     ->color('warning')
                //     ->icon(Heroicon::ArrowUpTray)
                //     ->action(function (Table $table) {
                //         // dd();
                //         // $table->getVisibleColumns()
                //         return 
                //     }),

            Action::make('importExcel')
                ->label('Importa Valori')
                ->color('warning')
                ->icon(Heroicon::ArrowUpTray)
                ->schema([
                    FileUpload::make('filename')
                        ->label('Carica file excel')
                        ->openable()
                        // ->directory('task_import_files')
                        ->visibility('public')
                        ->storeFiles(false)
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                ])
                ->action(function (array $data): void {
                    if (array_key_exists('filename', $data)) {
                        try {
                            $date = Carbon::now();
                            $file = $data['filename'];
                            $extension = $file->getClientOriginalExtension();
                            $originalName = $file->getClientOriginalName();
                            $newName = $date->format('Ymd') . '_' . $date->format('Hmi');
                            $path = $file->storeAs('tasks_import_values', $newName . '.' . $extension);
                            $savedata = [
                                'status' => 'File Caricato',
                                'path' => $path,
                                'filename' => $originalName
                            ];
                            $taskImportFile = TaskValuesImportFile::create($savedata);
                            $recipient = auth()->user();
                            // Excel::import(new UsersImport, 'users.xlsx');
                            ImportTaskValues::dispatch($taskImportFile->id)->onQueue('tasks');
                            Notification::make()
                                ->title('Importazione Valori')
                                ->title('File ' . $originalName . ' caricato')
                                ->sendToDatabase($recipient);
                        } catch (\Throwable $th) {
                            $recipient = auth()->user();
                            Notification::make()
                                ->title('Errore Importazione Valori')
                                ->body($th->getMessage())
                                ->sendToDatabase($recipient);
                        }
                    } else {
                        Notification::make()
                            ->title('Nessun file caricato!')
                            ->warning()
                            ->send();
                    }
                })

            ]);
    }
}
