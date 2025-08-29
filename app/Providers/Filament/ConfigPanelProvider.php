<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectIfNotFilamentAuthenticated;
use Filafly\Themes\Brisk\BriskTheme;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class ConfigPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('config')
            ->path('config')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/filafly_brisk/theme.css')
            ->plugin(BriskTheme::make())
            ->sidebarCollapsibleOnDesktop()
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
            // ->maxContentWidth(Width::Full)
            // ->subNavigationPosition(SubNavigationPosition::Top)
            ->unsavedChangesAlerts()
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
            ])
            ->databaseNotifications(isLazy: true)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                RedirectIfNotFilamentAuthenticated::class,
            ])->plugins([
                FilamentEditProfilePlugin::make()
                    ->shouldShowEmailForm(false)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
            ])->userMenuItems([
                'profile' => Action::make('profile')
                    ->label(fn() => Auth::user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ]);
    }
}
