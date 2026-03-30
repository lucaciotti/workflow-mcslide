<?php

namespace App\Filament\Resources\TaskMissings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TaskMissingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('task_id')
                    ->relationship('task', 'id'),
                Select::make('component_id')
                    ->relationship('component', 'id'),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name'),
                Toggle::make('stock_available'),
                Toggle::make('stock_not_available'),
                Toggle::make('supplier_request'),
                TextInput::make('ref_supplier_doc')
                    ->default(''),
                DatePicker::make('ref_supplier_date_start'),
                DatePicker::make('ref_supplier_date_end'),
                Toggle::make('purchased'),
                TextInput::make('qty')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
