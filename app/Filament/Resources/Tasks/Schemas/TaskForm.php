<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\TaskTypes;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        $attrRepeaters=[];
        foreach (AttributeCategory::all() as $cat) {
            array_push(
                $attrRepeaters,
                Repeater::make($cat->name)
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
            );
        }
        return $schema
            ->components([
                Select::make('type')
                    ->options(TaskTypes::array())
                    ->default('ord')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('num')
                    ->numeric()
                    ->default(0),
                Select::make('customer_id')
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
                // TextInput::make('shipping_address_id')
                //     ->numeric(),
                TextInput::make('carrier')
                    ->default(''),
                DatePicker::make('date_shipping'),
                Toggle::make('box_glass'),
                Select::make('product_range_id')
                    ->relationship('productRange', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),

                ...$attrRepeaters
                
                    
            ]);
    }
}