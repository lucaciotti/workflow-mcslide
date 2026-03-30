<?php

namespace App\Filament\Config\Resources\Components\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('unit')
                    ->required()
                    ->default(''),
                TextInput::make('barcode'),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name'),
            ]);
    }
}
