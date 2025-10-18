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
                IconEntry::make('imported')
                    ->boolean(),
                TextEntry::make('date_last_import')
                    ->date(),
                IconEntry::make('warning')
                    ->boolean(),
                TextEntry::make('type'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('num')
                    ->numeric(),
                TextEntry::make('customer.name'),
                IconEntry::make('box_glass')
                    ->boolean(),
                TextEntry::make('workflow_state_id')
                    ->numeric(),
            ]);
    }
}
