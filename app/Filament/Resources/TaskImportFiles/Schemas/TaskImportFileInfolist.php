<?php

namespace App\Filament\Resources\TaskImportFiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskImportFileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('filename'),
                TextEntry::make('path'),
                TextEntry::make('status'),
                TextEntry::make('date_upload')
                    ->date(),
                TextEntry::make('date_last_import')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
