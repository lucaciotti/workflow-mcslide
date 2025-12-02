<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Enums\TaskTypes;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Kirschbaum\Commentions\Filament\Actions\CommentsAction;
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
        foreach (AttributeCategory::all() as $cat) {
            $singleAttrRepeaters = [];
            foreach (Attribute::where('attribute_category_id', $cat->id)->get() as $attr) {
                array_push(
                    $singleAttrRepeaters,
                    TextColumn::make($attr->name)
                        ->getStateUsing(fn($record) =>
                        $record->attributeValues()
                            ->where('attribute_id', $attr->id)->first()->value ?? '-')
                        ->label($attr->name)
                        ->sortable()
                        ->toggleable(),
                );                
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
                TextColumn::make('productRange.name')->label('Fam.Prodotto')
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
                ...$attrRepeaters,
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
                Action::make('transition')->label('Modifica Stato       ')->outlined()->icon(Heroicon::ArrowRightStartOnRectangle)->color('warning')
                    ->schema([
                        Select::make('state_id')
                            ->searchable()
                            ->options(function (Task $record): array {
                                $transitions = WorkflowTransition::with(['fromState', 'toState'])->get();
                                $states = $transitions
                                    ->pluck('fromState')
                                    ->filter(fn(?WorkflowState $state) => !is_null($state))
                                    ->merge($transitions->pluck('toState'))
                                    ->unique();
                                $states_ids = $states->pluck('id')->toArray();
                                if (in_array($record->workflow_state_id, $states_ids)) {
                                    return WorkflowTransition::query()->with(['fromState', 'toState'])->where('from_state_id', $record->workflow_state_id)->get()->pluck('toState.name', 'toState.id')->toArray();
                                } else {
                                    return WorkflowState::query()->pluck('name', 'id')->toArray();
                                }
                            })
                    ])
                    ->action(function (array $data, Task $record) {
                        // dd((int) $data['state_id']);
                        $record->workflow_state_id = (int) $data['state_id'];
                        $record->save();
                    }),
                CommentsAction::make()
                    ->mentionables(User::all()),
                EditAction::make()->hiddenLabel(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('Genera Report')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadHTML(
                                    Blade::render('export.pdf.task', ['records' => $records])
                                )->setPaper('a4', 'landscape')->stream();
                            }, 'Ordini_pianificati.pdf');
                        }),

                // DeleteBulkAction::make(),
                ]),
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->except([
                        'created_at',
                        'updated_at',
                    ])->ignoreFormatting([
                        'date',
                        'date_shipping',
                        'num'
                    ]),
                ]),
                ManageTablePresetAction::make()->label('')->outlined(),
            ]);
    }
}
