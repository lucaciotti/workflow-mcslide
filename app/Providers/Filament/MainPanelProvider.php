<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Tasks\Widgets\TaskOverview;
use App\Http\Middleware\RedirectIfNotFilamentAuthenticated;
use Filafly\Themes\Brisk\BriskTheme;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Resma\FilamentAwinTheme\FilamentAwinTheme;

class MainPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('main')
            ->path('/')
            ->login()
            // ->registration()
            ->passwordReset()
            ->colors([
                'primary' => Color::Cyan,
            ])
            // Theme Default
            // ->viteTheme('resources/css/filament/default/theme.css')
            // Theme Brisk
            ->viteTheme('resources/css/filament/filafly_brisk/theme.css')
            ->plugin(BriskTheme::make())
            // Theme Awin
            // ->viteTheme('resources/css/filament/resma_awin/theme.css')
            // ->plugin(FilamentAwinTheme::make())
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications(isLazy: true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                TaskOverview::class,
            ])
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
