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
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

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
                DatePicker::make('date_shipping')->label('Data Spedizione'),
                Select::make('product_range_id')->label('Famiglia Prodotto')
                    ->relationship('productRange', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Toggle::make('box_glass')->label('Vetro'),
                Toggle::make('compensatori')->label('Compensatori'),
                Toggle::make('binari')->label('Lav.su Binari'),
                ]),
                Section::make('Gestione')->schema([
                    Fieldset::make('Stato')->schema([
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
                    ]),
                    Fieldset::make('Mancanti')->schema([
                        Action::make('mancanti')->label('Dichiara Codici Mancanti')->outlined()->extraAttributes(['class' >= 'w-full'])->icon(Heroicon::ArrowRightStartOnRectangle)->color('warning')
                            ->schema([
                                TextInput::make('codice')
                                ->required()
                                ->maxLength(255),
                            ])
                            ->action(function (array $data, Task $record) {
                                // dd((int) $data['state_id']);
                                // $record->workflow_state_id = (int) $data['state_id'];
                                // $record->save();
                            }),
                    ]),
                ]),
                ...$attrRepeaters
                
                    
            ]);
    }
}