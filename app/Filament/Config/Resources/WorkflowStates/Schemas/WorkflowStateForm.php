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
                Select::make('department_id')->label('Reparto')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Gestione Gate')->columnSpan(2)->columns(2)->schema([
                    Toggle::make('enable_gate')->label('Abilita')->live(),
                    TextInput::make('gate_days')->label('n.Giorni Default Gate')
                        ->numeric()
                        ->default(0)
                        ->visible(fn(Get $get) => $get('enable_gate')),
                ]),
                // Select::make('permissions')
                //     ->label('Ruoli')
                //     ->relationship('permissions', 'name')->multiple()
                //     ->options(Role::query()->pluck('name', 'id'))
            ]);
    }
}
