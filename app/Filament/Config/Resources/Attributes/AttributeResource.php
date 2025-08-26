<?php

namespace App\Filament\Config\Resources\Attributes;

use App\Filament\Config\Resources\Attributes\Pages\CreateAttribute;
use App\Filament\Config\Resources\Attributes\Pages\EditAttribute;
use App\Filament\Config\Resources\Attributes\Pages\ListAttributes;
use App\Filament\Config\Resources\Attributes\Pages\ViewAttribute;
use App\Filament\Config\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Config\Resources\Attributes\Schemas\AttributeInfolist;
use App\Filament\Config\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $recordTitleAttribute = 'Attributo';
    protected static ?string $modelLabel = 'attributo';
    protected static ?string $pluralModelLabel = 'attributi';

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributesTable::configure($table);
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
            'index' => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'view' => ViewAttribute::route('/{record}'),
            'edit' => EditAttribute::route('/{record}/edit'),
        ];
    }
}
