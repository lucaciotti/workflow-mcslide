<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TempTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('task_id')
                    ->numeric(),
                Toggle::make('imported')
                    ->required(),
                DatePicker::make('date_last_import'),
                Toggle::make('selected')
                    ->required(),
                Toggle::make('warning')
                    ->required(),
                Textarea::make('error')
                    ->columnSpanFull(),
                TextInput::make('num_row')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('type')
                    ->required()
                    ->default('ord'),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('num')
                    ->numeric()
                    ->default(0),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                Select::make('shipping_address_id')
                    ->relationship('shippingAddress', 'name'),
                TextInput::make('carrier')
                    ->default(''),
                DatePicker::make('date_shipping'),
                Toggle::make('box_glass'),
                Select::make('product_range_id')
                    ->relationship('productRange', 'name'),
                TextInput::make('workflow_state_id')
                    ->numeric(),
            ]);
    }
}
