<?php

namespace App\Filament\Config\Resources\Attributes\Schemas;

use App\Enums\AttributeTypes;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome Attributo')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Tipologia')
                    ->options(AttributeTypes::array())
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Reparto')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nome Reparto')
                            ->required()
                            ->maxLength(255)
                    ])
                    ->required(),
            ]);
    }
}
