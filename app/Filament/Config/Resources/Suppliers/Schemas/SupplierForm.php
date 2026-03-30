<?php

namespace App\Filament\Config\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->default(''),
                TextInput::make('area')
                    ->default(''),
                TextInput::make('provincia')
                    ->default(''),
            ]);
    }
}
