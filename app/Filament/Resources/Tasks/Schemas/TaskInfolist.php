<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('num')
                    ->numeric(),
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('shipping_address_id')
                    ->numeric(),
                TextEntry::make('carrier'),
                TextEntry::make('date_shipping')
                    ->date(),
                IconEntry::make('box_glass')
                    ->boolean(),
                TextEntry::make('product_range_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
