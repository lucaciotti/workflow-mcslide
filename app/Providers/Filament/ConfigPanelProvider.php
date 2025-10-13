<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Core\Traits\HasCorePanel;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class ConfigPanelProvider extends PanelProvider
{
    use HasCorePanel;
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('config')
            ->path('config')
            ->discoverResources(in: app_path('Filament/Config/Resources'), for: 'App\Filament\Config\Resources')
            ->discoverPages(in: app_path('Filament/Config/Pages'), for: 'App\Filament\Config\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Config/Widgets'), for: 'App\Filament\Config\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->plugins([
                \TomatoPHP\FilamentUsers\FilamentUsersPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
                    ->navigationGroup('System')
                    ->navigationSort(2)
                    // ->navigationIcon(Heroicon::OutlinedDocumentText)
                    ->navigationLabel('Log Viewer')
                    // ->authorize(fn(): bool => auth()->user()->can('view-logs')),
                // Other plugins
            ]);
    }
}
