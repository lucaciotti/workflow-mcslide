<?php

namespace App\Filament\Resources\TaskImportFiles\Resources\TempTasks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TempTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('task_id')
                    ->numeric(),
                IconEntry::make('imported')
                    ->boolean(),
                TextEntry::make('date_last_import')
                    ->date(),
                IconEntry::make('selected')
                    ->boolean(),
                IconEntry::make('warning')
                    ->boolean(),
                TextEntry::make('num_row')
                    ->numeric(),
                TextEntry::make('type'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('num')
                    ->numeric(),
                TextEntry::make('customer.name'),
                TextEntry::make('shippingAddress.name'),
                TextEntry::make('carrier'),
                TextEntry::make('date_shipping')
                    ->date(),
                IconEntry::make('box_glass')
                    ->boolean(),
                TextEntry::make('productRange.name'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('workflow_state_id')
                    ->numeric(),
            ]);
    }
}
