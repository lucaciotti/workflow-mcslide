<?php

namespace App\Providers\Filament\Core;

use Filafly\Themes\Brisk\BriskTheme;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Panel;
use Filament\Pages;
use Filament\Widgets;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class CorePanel extends Panel
{
    protected function setUp(): void
    {
        $this
            // Theme Default
            ->viteTheme('resources/css/filament/default/theme.css')
            // Theme Brisk
            // ->viteTheme('resources/css/filament/filafly_brisk/theme.css')
            // ->plugin(BriskTheme::make())
            // Theme Awin
            // ->viteTheme('resources/css/filament/resma_awin/theme.css')
            // ->plugin(FilamentAwinTheme::make())
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(asset('images/McSlide_logo.png'))
            ->brandName('McSlide App')
            ->brandLogoHeight('8rem')
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->databaseNotifications(isLazy: true)
            ->subNavigationPosition(SubNavigationPosition::Top)
            ->login()
            // ->registration()
            ->passwordReset()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->maxContentWidth(Width::Full)
            // ->subNavigationPosition(SubNavigationPosition::Top)
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->shouldShowEmailForm(false)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false),
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
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
                Authenticate::class,
            ]);
    }
}
