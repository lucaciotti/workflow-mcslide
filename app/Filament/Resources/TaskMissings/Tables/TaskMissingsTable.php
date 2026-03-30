<?php

namespace App\Filament\Resources\TaskMissings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaskMissingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task.num')->label('Num. CO')
                    ->searchable(),
                TextColumn::make('task.date')->label('Data CO')
                    ->searchable(),
                TextColumn::make('task.customer.name')->label('Cliente CO')
                    ->searchable(),
                TextColumn::make('component.code')->label('Codice Componente')
                    ->searchable(),
                TextColumn::make('qty')->label('Qta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('supplier.name')->label('Fornitore')
                    ->searchable(),
                IconColumn::make('stock_available')->label('Disponibile in Magazzino')
                    ->boolean(),
                IconColumn::make('stock_not_available')->label('Non Disponibile in Magazzino')
                    ->boolean(),
                IconColumn::make('supplier_request')->label('Inviato Ordine Lavorazione Fornitore')
                    ->boolean(),
                TextColumn::make('ref_supplier_doc')->label('Rif. Ord. Fornitore')
                    ->searchable(),
                TextColumn::make('ref_supplier_date_start')->label('Data Ord. Forn.')
                    ->date()
                    ->sortable(),
                TextColumn::make('ref_supplier_date_end')->label('Data Ord. Forn. Completato')
                    ->date()
                    ->sortable(),
                IconColumn::make('purchased')->label('Conferma consegna')
                    ->boolean(),
                TextColumn::make('created_at')->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Aggiornato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
