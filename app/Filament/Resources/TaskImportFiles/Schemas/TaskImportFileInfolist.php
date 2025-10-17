<?php

namespace App\Filament\Resources\TaskImportFiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskImportFileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(3)
            ->components([
                TextEntry::make('status')->label('Stato'),
                TextEntry::make('date_upload')->label('Data upload')
                    ->date(),
                TextEntry::make('date_last_import')->label('Data processato')
                    ->date(),
            ]);
    }
}
