<?php

namespace App\Filament\Config\Resources\WorkflowStates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class WorkflowStateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome Stato')
                    ->required(),
                Section::make('Gestione Gate')->schema([
                    Toggle::make('is_gate')->live(),
                    TextInput::make('gate_days')
                        ->numeric()
                        ->default(0)
                        ->visible(fn(Get $get) => $get('is_gate')),
                ]),
                Select::make('permissions')
                    ->label('Ruoli')
                    ->relationship('permissions', 'name')->multiple()
                    ->options(Role::query()->pluck('name', 'id'))
            ]);
    }
}
