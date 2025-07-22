<?php

//namespace App\Filament\Resources\RepairSheetResource\Widgets;
namespace App\Filament\Widgets;

use App\Models\RepairSheet;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RepairSheetStats extends BaseWidget {

    protected function getStats(): array {

        return [

            Stat::make('Total Fiches de réparation', RepairSheet::count())
                ->description('Tous les fiches de reparation')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Cloturée', RepairSheet::where('status', 'delivered')->count())
                ->description('Cloturée')
                ->color('success'),

            Stat::make('En attente', RepairSheet::where('status', 'pending')->count())
                ->description('En attente de confirmation')
                ->color('success'),
        ];
    }
}
