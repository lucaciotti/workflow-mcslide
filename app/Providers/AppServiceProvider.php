<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    // public $singletons = [
    //     // \Filament\Http\Responses\Auth\Contracts\LoginResponse::class => \App\Http\Responses\LoginResponse::class,
    //     \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class => \App\Http\Responses\LogoutResponse::class,
    // ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch->modalHeading('App');
            // $panelSwitch->panels(['admin','dev','app']);
            // $panelSwitch->sort(order: 'desc') 
            // $panelSwitch->modalWidth('sm');
            // $panelSwitch->slideOver();
            $panelSwitch->simple();
            $panelSwitch
                ->labels([
                    'main' => 'Home',
                    'config' => __('Configurazioni')
                ]);
            $panelSwitch->icons([
                'main' => 'heroicon-o-home',
                'config' => 'heroicon-o-cog-6-tooth',
            ], $asImage = false);
            // This would result in an icon/image size of 128 pixels.
            $panelSwitch->iconSize(32);
            // $panelSwitch->canSwitchPanels(fn(): bool => auth()->user()?->can('switch_panels'));
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->reorderableColumns()
                ->striped()
                ->filtersTriggerAction(
                    fn(Action $action) => $action
                        ->slideOver()
                        ->button(),
                )
                // ->filtersLayout(FiltersLayout::AboveContentCollapsible)
                ->paginationPageOptions([10, 25, 50])
                ->deferColumnManager(false);
        });
    }
}
