<?php

namespace App\Filament\Config\Resources\Attributes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label('Nome Attributo'),
                TextEntry::make('type')
                ->label('Tipologia'),
                TextEntry::make('attribute_category.name')
                ->label('Categoria'),
                TextEntry::make('Data')
                ->label('Data Creazione')
                    ->dateTime(),
                TextEntry::make('updated_at')
                ->label('Data Ultima Modifica')
                    ->dateTime(),
            ]);
    }
}
