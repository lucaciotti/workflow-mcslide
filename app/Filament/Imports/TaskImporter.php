<?php

namespace App\Filament\Imports;

use App\Models\Task;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class TaskImporter extends Importer
{
    protected static ?string $model = Task::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('num')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('customer')
                ->relationship(),
            ImportColumn::make('shipping_address_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('carrier')
                ->rules(['max:100']),
            ImportColumn::make('date_shipping')
                ->rules(['date']),
            ImportColumn::make('box_glass')
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('productRange')
                ->relationship(),
        ];
    }

    public function resolveRecord(): Task
    {
        return Task::firstOrNew([
            'num' => $this->data['num'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your task import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
