<?php

namespace App\Filament\Config\Resources\ErpStates;

use App\Filament\Config\Resources\ErpStates\Pages\CreateErpState;
use App\Filament\Config\Resources\ErpStates\Pages\EditErpState;
use App\Filament\Config\Resources\ErpStates\Pages\ListErpStates;
use App\Filament\Config\Resources\ErpStates\Schemas\ErpStateForm;
use App\Filament\Config\Resources\ErpStates\Tables\ErpStatesTable;
use App\Models\ErpState;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ErpStateResource extends Resource
{
    protected static ?string $model = ErpState::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;
    
    protected static string | UnitEnum | null $navigationGroup = 'Gestione WorkFlow';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Stato Gestionale ERP';
    protected static ?string $pluralModelLabel = 'Stati Gestionale ERP';

    public static function form(Schema $schema): Schema
    {
        return ErpStateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpStatesTable::configure($table);
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
            'index' => ListErpStates::route('/'),
            // 'create' => CreateErpState::route('/create'),
            // 'edit' => EditErpState::route('/{record}/edit'),
        ];
    }
}
