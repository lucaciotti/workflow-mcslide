<?php

namespace App\Filament\Config\Resources\ProductRanges;

use App\Filament\Config\Resources\ProductRanges\Pages\CreateProductRange;
use App\Filament\Config\Resources\ProductRanges\Pages\EditProductRange;
use App\Filament\Config\Resources\ProductRanges\Pages\ListProductRanges;
use App\Filament\Config\Resources\ProductRanges\Pages\ViewProductRange;
use App\Filament\Config\Resources\ProductRanges\Schemas\ProductRangeForm;
use App\Filament\Config\Resources\ProductRanges\Schemas\ProductRangeInfolist;
use App\Filament\Config\Resources\ProductRanges\Tables\ProductRangesTable;
use App\Models\ProductRange;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductRangeResource extends Resource
{
    protected static ?string $model = ProductRange::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InboxStack;

    protected static ?string $recordTitleAttribute = 'Famiglia Prodotto';
    protected static ?string $modelLabel = 'Famiglia Prodotto';
    protected static ?string $pluralModelLabel = 'Famiglia Prodotti';

    public static function form(Schema $schema): Schema
    {
        return ProductRangeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductRangeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductRangesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductRanges::route('/'),
            'create' => CreateProductRange::route('/create'),
            'view' => ViewProductRange::route('/{record}'),
            'edit' => EditProductRange::route('/{record}/edit'),
        ];
    }
}
