<?php

// namespace App\Filament\Resources\AppointmentResource\Widgets;
namespace App\Filament\Widgets;

use App\Models\Appointment;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppointmentStats extends BaseWidget {

    protected static ?string $pollingInterval = null; // ou '10s' pour rafraîchissement auto

    protected function getStats(): array {

        return [
            Stat::make('Total rendez-vous', Appointment::count())
                ->description('Tous les rendez-vous')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Rendez-vous aujourd\'hui', Appointment::whereDate('selected_date', today())->count())
                ->description("Date: " . now()->format('d/m/Y'))
                ->color('success'),

            Stat::make('En attente', Appointment::where('status', 'pending')->count())
                ->description('En attente de confirmation')
                ->color('warning'),
        ];
    }
}
