<?php

namespace App\Filament\Resources\Tasks\Tables;

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
use Filament\Tables\Table;

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
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('num')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('shipping_address_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('carrier')
                    ->searchable(),
                // TextColumn::make('date_shipping')
                //     ->date()
                //     ->sortable(),
                // IconColumn::make('box_glass')
                //     ->boolean(),
                // TextColumn::make('product_range_id')
                //     ->numeric()
                //     ->sortable(),
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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
