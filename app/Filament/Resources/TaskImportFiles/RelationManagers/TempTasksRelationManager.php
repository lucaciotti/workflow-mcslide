<?php

namespace App\Filament\Resources\TaskImportFiles\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TempTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tempTasks';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('task_id')
                    ->numeric(),
                Toggle::make('imported')
                    ->required(),
                DatePicker::make('date_last_import'),
                Toggle::make('selected')
                    ->required(),
                Toggle::make('warning')
                    ->required(),
                Textarea::make('error')
                    ->columnSpanFull(),
                TextInput::make('num_row')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('type')
                    ->required()
                    ->default('ord'),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('num')
                    ->numeric()
                    ->default(0),
                TextInput::make('customer_id')
                    ->numeric(),
                TextInput::make('shipping_address_id')
                    ->numeric(),
                TextInput::make('carrier')
                    ->default(''),
                DatePicker::make('date_shipping'),
                Toggle::make('box_glass'),
                TextInput::make('product_range_id')
                    ->numeric(),
                TextInput::make('workflow_state_id')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('num_row')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('num')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->sortable(),
                TextColumn::make('shippingAddress.name')
                    ->sortable(),
                TextColumn::make('carrier')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_shipping')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('box_glass')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('product_range_id')
                    ->sortable(),
                TextColumn::make('workflow_state_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('imported')
                    ->boolean(),
                TextColumn::make('date_last_import')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('selected')
                    ->boolean(),
                IconColumn::make('warning')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
