<?php

namespace App\Providers\Filament;

use App\Filament\Resources\RepairSheetResource\Widgets\RepairSheetStats as WidgetsRepairSheetStats;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

use Filament\Widgets;
use App\Filament\Widgets\AppointmentStats;
use App\Filament\Widgets\RepairSheetStats;
//use App\Filament\Widgets\RepairSheetStatusTable;

use Filament\Navigation\MenuItem;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider {

    public function panel(Panel $panel): Panel {

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
                'secondary' => Color::Amber,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Inter')
            ->favicon('storage/images/mecatronik-logo.ico')
            ->collapsibleNavigationGroups(false)
            ->brandName('Car-repair Admin Panel') // ou ton nom
            ->brandLogo(asset('storage/images/logos/mecatronik-logo.png'))
            ->brandLogoHeight('4.5rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                AppointmentStats::class,
                RepairSheetStats::class,
                //RepairSheetStatusTable::class,
            ])
            ->navigationGroups([                
                'Repair cars Management',
                'Services Management',
                'Contacts Management',
                'Settings Management',
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
