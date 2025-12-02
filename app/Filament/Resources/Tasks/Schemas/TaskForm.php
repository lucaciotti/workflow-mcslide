<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\TaskTypes;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\Task;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
        foreach (AttributeCategory::all() as $cat) {
            array_push(
                $attrRepeaters,
                Section::make($cat->name)->columnSpan(2)->collapsible()->schema([
                Repeater::make($cat->name)->columns(2)->hiddenLabel()
                // ->table([
                //     TableColumn::make('Nome Attributo')->wrapHeader(),
                //     TableColumn::make('Valore')->colum(),
                //     TableColumn::make('Valore')->wrapHeader(),
                // ])
                ->addActionLabel('Aggiungi Attributo')->label($cat->name)
                ->relationship('attributeValues', modifyQueryUsing: fn(Builder $query) => $query->whereHas('attribute', function ($q) use ($cat) {
                    $q->where('attribute_category_id', $cat->id);
                }),)
                ->schema([
                    Select::make('attribute_id')
                        ->label('Name')
                        ->options(Attribute::where('attribute_category_id', $cat->id)->pluck('name', 'id'))
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
                ]),
                Section::make('Stato')->schema([
                    Select::make('workflow_state_id')->hiddenLabel()
                        ->relationship('workFlowState', 'name')
                        ->disabled(true)
                        ->searchable()
                        ->preload(),
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
                                if(in_array($record->workflow_state_id, $states_ids)){
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
                ]),
                ...$attrRepeaters
                
                    
            ]);
    }
}