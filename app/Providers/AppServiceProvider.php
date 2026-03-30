<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Livewire\Notifications;
use Filament\Panel as Panel;
use Filament\Support\Assets\Js;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentUsers\Filament\Resources\Users\Schemas\UserForm;
use TomatoPHP\FilamentUsers\Filament\Resources\Users\Tables\UsersTable;

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
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
            request()->server->set('HTTPS', request()->header('X-Forwarded-Proto', 'https') == 'https' ? 'on' : 'off');
        }
        // FilamentAsset::register([
        //     Js::make('custom-fullscreen', __DIR__ . '/../../resources/js/custom-fullscreen.js'),
        // ]);
        // Panel::configureUsing(function (Panel $panel): void {
        //     $panel->maxContentWidth(Width::Full);
        // });
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
                    'config' => __('Configurazioni'),
                    'admin' => 'App Maintenance',
                ]);
            $panelSwitch->panels(['main', 'config', 'admin']);
            $panelSwitch->icons([
                'main' => 'heroicon-o-home',
                'config' => 'heroicon-o-cog-6-tooth',
                'admin' => 'heroicon-o-cog-6-tooth',
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
                // ->columnManagerTriggerAction(
                //     fn (Action $action) => $action
                //         ->slideOver()
                //         ->hiddenLabel(),
                // )
                // ->filtersLayout(FiltersLayout::AboveContentCollapsible)
                ->paginationPageOptions([25, 50, 100])
                ->defaultPaginationPageOption(50)
                ->deferFilters(false)
                ->deferColumnManager(false);
        });

        Notifications::alignment(Alignment::Center);

        UserForm::register([
            Select::make('department_id')
                ->label('Reparto di Riferimento')
                ->relationship('department', 'name')
                ->columnSpan(2)
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nome Reparto')
                        ->required()
                        ->maxLength(255)
                ]),
        ]);
        UsersTable::register([
            TextColumn::make('department.name')->label('Reparto')
                ->searchable(),
        ]);
        // UserFilters::register([
        //     \Filament\Tables\Filters\SelectFilter::make('something')
        // ]);
    }
}
