<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Tasks\Widgets\TaskOverview;
use App\Providers\Filament\Core\Traits\HasCorePanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;

class MainPanelProvider extends PanelProvider
{
    use HasCorePanel;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('main')
            ->path('/')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                TaskOverview::class,
            ]);
    }
}
