<?php

namespace App\Filament\Resources\Tasks\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskOverview extends StatsOverviewWidget
{
    protected function getHeading(): ?string
    {
        return 'Analytics';
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Ordini Pianificati', Task::query()->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
