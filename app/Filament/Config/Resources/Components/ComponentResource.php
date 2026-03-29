<?php

namespace App\Filament\Config\Resources\Components;

use App\Filament\Config\Resources\Components\Pages\CreateComponent;
use App\Filament\Config\Resources\Components\Pages\EditComponent;
use App\Filament\Config\Resources\Components\Pages\ListComponents;
use App\Filament\Config\Resources\Components\Schemas\ComponentForm;
use App\Filament\Config\Resources\Components\Tables\ComponentsTable;
use App\Models\Component;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static string | UnitEnum | null $navigationGroup = 'Anagrafiche';
    protected static ?string $recordTitleAttribute = 'code';
    protected static ?string $modelLabel = 'codice componente';
    protected static ?string $pluralModelLabel = 'codici componenti';

    public static function form(Schema $schema): Schema
    {
        return ComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComponentsTable::configure($table);
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
            'index' => ListComponents::route('/'),
            // 'create' => CreateComponent::route('/create'),
            // 'edit' => EditComponent::route('/{record}/edit'),
        ];
    }
}
