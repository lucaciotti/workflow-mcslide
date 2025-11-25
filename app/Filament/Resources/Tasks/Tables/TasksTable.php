<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Enums\TaskTypes;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        $attrRepeaters = [];
        foreach (AttributeCategory::all() as $cat) {
            foreach (Attribute::where('attribute_category_id', $cat->id)->get() as $attr) {
                array_push(
                    $attrRepeaters,
                    ColumnGroup::make($cat->name, [
                        TextColumn::make($attr->name)
                            ->getStateUsing(fn($record) =>
                            $record->attributeValues()
                                ->where('attribute_id', $attr->id)->first()->value ?? '-')
                            ->label($attr->name)
                            ->sortable()
                            ->toggleable(),
                    ])
                        ->alignment(Alignment::Center)
                        ->wrapHeader(),
                );
            }
        }
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipologia')
                    ->badge()
                    ->searchable(),
                TextColumn::make('workFlowState.name')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('num')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('shippingAddress.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('carrier.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('productRange.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('date_shipping')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('box_glass')
                    ->boolean()
                    ->toggleable(),
                ...$attrRepeaters,
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->label('Tipologia')
                    ->options(TaskTypes::class),
                DateRangeFilter::make('date')->label('Data Ordine'),
                // DateRangeFilter::make('updated_at')->label('Data ultima modifica'),
                SelectFilter::make('customer')->label('Clienti')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('productRange_id')->label('Famiglia Prodotto')
                    ->relationship('productRange', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
