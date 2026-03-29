<?php

namespace App\Filament\Config\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
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
