<?php

namespace App\Filament\Config\Resources\ErpStates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ErpStateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
