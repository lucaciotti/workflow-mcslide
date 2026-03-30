<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\TaskTypes;
use App\Helpers\Workflow;
use App\Models\Attribute;
use App\Models\Department;
use App\Models\Task;
use App\Models\TaskWorkflowStory;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use DateTime;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        $attrRepeaters=[];
        foreach (Department::all() as $cat) {
            array_push(
                $attrRepeaters,
                Section::make($cat->name)->columnSpan(2)->collapsible()->schema([
                Repeater::make($cat->name)->columns(2)->hiddenLabel()
                ->addActionLabel('Aggiungi Attributo')->label($cat->name)
                ->relationship('attributeValues', modifyQueryUsing: fn(Builder $query) => $query->whereHas('attribute', function ($q) use ($cat) {
                    $q->where('department_id', $cat->id);
                }),)
                // ->table([
                //     TableColumn::make('Nome Attributo')->wrapHeader(),
                //     TableColumn::make('Valore'),
                //     // TableColumn::make('Valore')->wrapHeader(),
                // ])->compact()
                ->schema([
                    Select::make('attribute_id')
                        ->label('Name')
                        ->options(Attribute::where('department_id', $cat->id)->pluck('name', 'id'))
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->required()
                        ->live(),
                    TextInput::make('num_value')
                        ->label('Valore')
                        ->numeric()
                        // ->visible(fn(Get $get): bool => Attribute::find($get('attribute_id'))?->type->value == 'num')
                        ->visible(function (TextInput $component) {
                            $uuidContainer = array_last(explode(".", $component->getContainer()->getStatePath()));
                            $attribute_id = $component->getContainer()->getParentComponent()->getState()[$uuidContainer]['attribute_id'];
                            return $attribute_id ? Attribute::find($attribute_id)?->type->value == 'num' : false;
                        }),
                    TextInput::make('string_value')
                        ->label('Valore')
                        // ->visible(fn(Get $get): bool => $get('attribute_id')!=null ? Attribute::find($get('attribute_id'))?->type->value == 'string' : false)
                        ->visible(function (TextInput $component) {
                            $uuidContainer = array_last(explode(".", $component->getContainer()->getStatePath()));
                            $attribute_id = $component->getContainer()->getParentComponent()->getState()[$uuidContainer]['attribute_id'];
                            return $attribute_id ? Attribute::find($attribute_id)?->type->value == 'string' : false;
                        }),
                    Select::make('bool_value')
                        ->label('Valore')
                        ->options([true => 'Sì', false => 'No'])
                        // ->visible(fn(Get $get): bool => Attribute::find($get('attribute_id'))?->type->value == 'bool')
                        ->visible(function (Select $component) {
                            $uuidContainer = array_last(explode(".", $component->getContainer()->getStatePath()));
                            $attribute_id = $component->getContainer()->getParentComponent()->getState()[$uuidContainer]['attribute_id'];
                            return $attribute_id ? Attribute::find($attribute_id)?->type->value == 'bool' : false;
                        }),
                    DatePicker::make('date_value')->label('Valore')
                        ->visible(function (DatePicker $component) {
                            $uuidContainer = array_last(explode(".", $component->getContainer()->getStatePath()));
                            $attribute_id = $component->getContainer()->getParentComponent()->getState()[$uuidContainer]['attribute_id'];
                            return $attribute_id ? Attribute::find($attribute_id)?->type->value == 'date' : false;
                        }),
                ]),
            ]),
            );
        }
        return $schema->columns(3)
            ->components([
                Section::make('Info Ordine')->columnSpan(2)->columns(2)->schema([
                Select::make('type')->label('Tipologia Ordine')
                    ->options(TaskTypes::array())
                    ->default('ord')
                    ->disabled()
                    ->required(),
                DatePicker::make('date')->label('Data Ordine')
                    ->disabled()
                    ->required(),
                TextInput::make('num')->label('Numero Ordine')
                    ->disabled()
                    ->numeric()
                    ->default(0),
                Select::make('customer_id')->label('Cliente')
                    ->disabled()
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('code')
                            ->required()
                            ->numeric(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('area')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('provincia')
                            ->required()
                            ->maxLength(2)
                    ])
                    ->required(),
                Select::make('shipping_address_id')->label('Destinazione Merce')
                    ->disabled()
                    ->relationship('shippingAddress', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // TextInput::make('shipping_address_id')
                //     ->numeric(),
                TextInput::make('carrier')->label('Vettore')
                    ->disabled()
                    ->default(''),
                DatePicker::make('date_shipping')->label('Data Spedizione')->columnSpan(2),
                Select::make('product_id')->label('Prodotto')
                    ->relationship('product', 'code')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                    ]),
                Select::make('product_range_id')->label('Famiglia Prodotto')
                    ->relationship('productRange', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Toggle::make('box_glass')->label('Vetro')->columnSpan(2),
                Toggle::make('compensatori')->label('Compensatori')->columnSpan(2),
                Toggle::make('binari')->label('Lav.su Binari')->columnSpan(2),
                ]),
                Section::make('Gestione')->schema([
                    Fieldset::make('Stato')->columns(2)->schema([
                        Select::make('workflow_state_id')->hiddenLabel()
                            ->relationship('workFlowState', 'name')
                            ->disabled(true)
                            ->searchable()
                            ->preload(),
                        Action::make('transition')->label('Modifica Stato       ')->outlined()->icon(Heroicon::ArrowRightStartOnRectangle)->color('warning')
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
                                    'workflow_state_id' => (int) $data['state_id'],
                                    'comment' => $data['comment'],
                                    ]);
                                $record->workflow_state_id = (int) $data['state_id'];
                                $record->save();
                            }),
                        ViewAction::make('workflow_story')->label('Storia Stati')->outlined()->icon(Heroicon::BarsArrowDown)->color('success')
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
                            ])
                    ]),
                    Fieldset::make('Mancanti')
                        ->visible(fn(Get $get, Task $record): bool => (WorkflowTransition::where('from_state_id', $get('workflow_state_id'))->where('subflow_missing', true)->first()?->subflow_missing ?? false) || ($record->has_missing ?? false))
                        ->schema([
                            Action::make('missing')->label('Dichiara Codici Mancanti')->outlined()->extraAttributes(['class' >= 'w-full'])->icon(Heroicon::OutlinedExclamationTriangle)->color('warning')
                                ->visible(fn(Get $get, Task $record): bool => (WorkflowTransition::where('from_state_id', $get('workflow_state_id'))->where('subflow_missing', true)->first()?->subflow_missing ?? false) && (!$record->has_missing ?? false))
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
                                ->action(function (array $data, Task $record, EditRecord $livewire) {
                                    // dd($record);
                                    if($record->missings->count()>0){
                                        $record->has_missing = true;
                                    }
                                    // CAMBIO ANCHE STATO al TASK
                                    $nextState = WorkflowTransition::where('from_state_id', $record->workflow_state_id)->where('subflow_missing', true)->first();
                                    $oldStory = TaskWorkflowStory::where('end', null)->where('task_id', $record->id)->first();
                                    if ($oldStory) {
                                        $oldStory->end = now();
                                        $oldStory->save();
                                    }
                                    $newStory = TaskWorkflowStory::create([
                                        'task_id' => $record->id,
                                        'workflow_state_id' => (int)  $nextState->toState->id,
                                        'comment' => 'SEGNALAZIONE MANCANTI!',
                                    ]);
                                    $record->workflow_state_id = (int)  $nextState->toState->id;
                                    $livewire->save();
                                    $livewire->refreshFormData([
                                        'status',
                                    ]);
                                    // dd((int) $data['state_id']);
                                    // $record->workflow_state_id = (int) $data['state_id'];
                                    // $record->save();
                                }),
                            ViewAction::make('missing_view')->label('Visualizza Codici Mancanti')->outlined()->extraAttributes(['class' >= 'w-full'])->icon(Heroicon::OutlinedExclamationTriangle)->color('success')
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
                        ]),
                ]),
                ...$attrRepeaters
                
                    
            ]);
    }
}