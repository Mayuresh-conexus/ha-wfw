<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\PatientsPerProgram;
use App\Filament\Widgets\AdminStatsWidget;
use App\Filament\Widgets\PatientTrendWidget;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->font('Inter')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('HealthApp Admin')
            ->brandLogo(asset('images/ha_logo.png'))
            ->brandLogoHeight('2.5rem')
            ->profile()
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->passwordReset()
            ->colors([
    'primary'   => Color::hex('#093E62'),  // main brand, buttons, active states
    'secondary' => Color::hex('#B9DFF9'),  // brand accent (light blue)
    'gray'      => Color::Slate,           // neutrals, borders, text hierarchy
    'info'      => Color::Sky,             // informational states
    'success'   => Color::Emerald,         // success states
    'warning'   => Color::Amber,           // warnings
    'danger'    => Color::Rose,            // errors, critical
])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Dashboard composition is handled by the custom zoned Dashboard page
                // (App\Filament\Pages\Dashboard), which embeds widgets explicitly per zone.
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
            ->plugins([
                FilamentShieldPlugin::make(),
                \Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
