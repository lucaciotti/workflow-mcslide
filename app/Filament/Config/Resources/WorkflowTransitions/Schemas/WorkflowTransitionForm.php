<?php

namespace App\Filament\Config\Resources\WorkflowTransitions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class WorkflowTransitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_state_id')
                    ->relationship('fromState', 'name', modifyQueryUsing: fn(Builder $query, Get $get) => $get('to_state_id') ? $query->where('id', '!=', $get('to_state_id')) : $query,)
                    ->live(),
                Select::make('to_state_id')
                    ->relationship('toState', 'name', modifyQueryUsing: fn(Builder $query, Get $get) => $get('from_state_id') ? $query->where('id', '!=', $get('from_state_id')) : $query,)
                    ->live(),
            ]);
    }
}
