<?php

namespace App\Filament\Config\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(1)
            ->components([
                TextInput::make('code')->label('Codice Prodotto')
                    ->required(),
                TextInput::make('description')->label('Descrizione'),
                Select::make('product_range_id')->label('Famiglia Prodotto')
                    ->relationship('productRange', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Gestione Gate')->collapsible()
                    ->schema([
                        Repeater::make('gates')->label('Gate')->columns(2)->hiddenLabel(true)->relationship()
                            ->table([
                                TableColumn::make('Stato'),
                                TableColumn::make('n.Giorni Gate')->width('200px'),
                            ])
                            // ->compact()
                            ->schema([
                                Select::make('workflow_state_id')
                                    ->label('Stato')
                                    ->relationship('workflowState', 'name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('gate_days')->label('n.Giorni Gate')
                                    ->visible()
                                    ->numeric(),
                            ]),
                    ]),
            ]);
    }
}
